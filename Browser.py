import sys
from PyQt5.QtCore import QUrl
from PyQt5.QtWidgets import QMainWindow,QApplication
from PyQt5.QtWebEngineWidgets import QWebEngineView
from PyQt5.QtGui import QIcon

class Window(QMainWindow):
    def __init__(self):
        super().__init__()

        self.setWindowTitle("Gimasha Service Center")
        self.setWindowIcon(QIcon("bg.jpg"))
        self.setGeometry(0,0,1600,900)
        #self.showMaximized()

        self.webEngineView = QWebEngineView()
        self.setCentralWidget(self.webEngineView)
        self.webEngineView.load(QUrl("http://localhost/GSSMS/login.html"))

app=QApplication(sys.argv)
Window = Window()
Window.show()
sys.exit(app.exec_())