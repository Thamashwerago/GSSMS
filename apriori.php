<?php
include "connection.php";

function apriori($transactions, $minSupport)
{
    $itemCounts = [];
    foreach ($transactions as $transaction) {
        foreach ($transaction as $item) {
            if (isset($itemCounts[$item])) {
                $itemCounts[$item]++;
            } else {
                $itemCounts[$item] = 1;
            }
        }
    }

    $itemsets = [];
    $supportCounts = [];
    foreach ($itemCounts as $item => $count) {
        if ($count / count($transactions) >= $minSupport) {
            $itemsets[] = ['itemset' => [$item], 'frequency' => $count];
            $supportCounts[implode(',', [$item])] = $count;
        }
    }

    $allFrequentItemsets = $itemsets;

    $k = 2;
    while (!empty($itemsets)) {
        $candidateItemsets = generateCandidates($itemsets, $k);

        $itemCounts = [];
        foreach ($transactions as $transaction) {
            foreach ($candidateItemsets as $candidate) {
                if (count(array_intersect($candidate['itemset'], $transaction)) == $k) {
                    $key = implode(",", $candidate['itemset']);
                    if (isset($itemCounts[$key])) {
                        $itemCounts[$key]++;
                    } else {
                        $itemCounts[$key] = 1;
                    }
                }
            }
        }

        $itemsets = [];
        foreach ($itemCounts as $itemset => $count) {
            if ($count / count($transactions) >= $minSupport) {
                $itemsets[] = ['itemset' => explode(",", $itemset), 'frequency' => $count];
                $supportCounts[$itemset] = $count;
            }
        }

        $allFrequentItemsets = array_merge($allFrequentItemsets, $itemsets);
        $k++;
    }

    return [$allFrequentItemsets, $supportCounts];
}

function generateCandidates($itemsets, $k)
{
    $candidates = [];
    $n = count($itemsets);
    for ($i = 0; $i < $n; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            $candidate = array_unique(array_merge($itemsets[$i]['itemset'], $itemsets[$j]['itemset']));
            if (count($candidate) == $k) {
                sort($candidate);
                $candidates[] = ['itemset' => $candidate, 'frequency' => 0];
            }
        }
    }
    return $candidates;
}

function generateAssociationRules($frequentItemsets, $supportCounts, $minConfidence, $totalTransactions)
{
    $rules = [];
    foreach ($frequentItemsets as $itemset) {
        if (count($itemset['itemset']) > 1) {
            $subsets = getSubsets($itemset['itemset']);
            foreach ($subsets as $subset) {
                $antecedent = $subset;
                $consequent = array_diff($itemset['itemset'], $subset);
                if (!empty($consequent)) {
                    $antecedentKey = implode(',', $antecedent);
                    $consequentKey = implode(',', $consequent);
                    $itemsetKey = implode(',', $itemset['itemset']);
                    $confidence = $supportCounts[$itemsetKey] / $supportCounts[$antecedentKey];
                    if ($confidence >= $minConfidence) {
                        $rules[] = [
                            'antecedent' => $antecedent,
                            'consequent' => $consequent,
                            'confidence' => $confidence
                        ];
                    }
                }
            }
        }
    }
    return $rules;
}

function getSubsets($itemset)
{
    $subsets = [];
    $count = count($itemset);
    $members = pow(2, $count);
    for ($i = 1; $i < $members - 1; $i++) {
        $b = sprintf("%0" . $count . "b", $i);
        $subset = [];
        for ($j = 0; $j < $count; $j++) {
            if ($b[$j] == '1') {
                $subset[] = $itemset[$j];
            }
        }
        $subsets[] = $subset;
    }
    return $subsets;
}

function groupData($data)
{
    $groupedData = [];
    foreach ($data as $item) {
        $billid = $item['billid'];
        if (!isset($groupedData[$billid])) {
            $groupedData[$billid] = [];
        }
        $groupedData[$billid][] = $item['id'];
    }
    return array_values($groupedData);
}

function search($conn, $sql)
{
    $data = array();
    $result = sqlsrv_query($conn, $sql);
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        array_push($data, $row);
    }
    sqlsrv_free_stmt($result);
    return $data;
}

$records = search($conn, "SELECT billid,id FROM billitem WHERE type='product'");

$transactions = groupData($records);

$minSupport = 0.0;
$minConfidence = 0.5;

list($frequentItemsets, $supportCounts) = apriori($transactions, $minSupport);

$associationRules = generateAssociationRules($frequentItemsets, $supportCounts, $minConfidence, count($transactions));

$json = [
    'frequentItemsets' => [],
    'associationRules' => []
];
foreach ($frequentItemsets as $value) {
    $productname = [];
    foreach ($value["itemset"] as $val) {
        $name = search($conn, "SELECT productname FROM products WHERE productid='" . $val . "'");
        $productname[] = $name[0]["productname"];
    }
    $json['frequentItemsets'][] = ['productName' => $productname, 'frequency' => $value['frequency']];
}

foreach ($associationRules as $rule) {
    $antecedentNames = [];
    foreach ($rule['antecedent'] as $val) {
        $name = search($conn, "SELECT productname FROM products WHERE productid='" . $val . "'");
        $antecedentNames[] = $name[0]["productname"];
    }
    $consequentNames = [];
    foreach ($rule['consequent'] as $val) {
        $name = search($conn, "SELECT productname FROM products WHERE productid='" . $val . "'");
        $consequentNames[] = $name[0]["productname"];
    }
    $json['associationRules'][] = [
        'antecedent' => $antecedentNames,
        'consequent' => $consequentNames,
        'confidence' => $rule['confidence']
    ];
}

echo json_encode($json);