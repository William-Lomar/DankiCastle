from selenium import webdriver
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.alert import Alert 
import time
import mysql.connector
from mysql.connector import errorcode
import requests
import sys
from datetime import timedelta, date,datetime

#Verificar se tem internet 
def check_internet():
    ''' checar conexão de internet '''
    url = 'https://www.google.com'
    timeout = 5
    try:
        requests.get(url, timeout=timeout)
        return True
    except Exception as e:
        print(e)
        return False  
    
if check_internet():
    print('Internet OK!')
else:
    print('Sem conexão à internet!')
    sys.exit()

#Cria conexão com banco de dados
connection = mysql.connector.connect(host='seu_host', user='seu_usuario', password='sua_senha', database='sua_database')

if connection.is_connected():
    print("Database conectada!") 
    
cursor = connection.cursor()

#Prepara os dados do log
sql = "INSERT INTO `log` VALUES (%s,%s,%s,%s)"
horaAtual = datetime.now().strftime('%d/%m/%Y %H:%M')
proxHora = (datetime.now()+timedelta(hours=16)).strftime('%d/%m/%Y %H:%M')
 
#Pre-configuração de profile
options = Options()
options.add_argument("user-data-dir=C:/seu_caminho/bot_python/SeleniumProfile")

#Instancia o driver como Chrome
driver = webdriver.Chrome(executable_path=ChromeDriverManager().install(),chrome_options=options)

#Config wait
wait = WebDriverWait(driver,100)

#Vai até a metamask
driver.get("chrome-extension://nkbihfbeogaeaoehlefnkodbefgpgknn/home.html")

#Espera até a senha aparecer na tela e entra com a senha e confirma com o enter
senhaMetamask = wait.until(EC.visibility_of_element_located((By.ID, 'password')))
senhaMetamask.send_keys("sua_senha"+Keys.RETURN)

#Abre uma nova aba e vai para DankiCastle
driver.execute_script("window.open('');") 
driver.switch_to.window(driver.window_handles[1]) #winwindow_handles[x] -> abas abertas
driver.get("https://app.dankicastle.io/")

#conexão com carteira
conectarCarteira = wait.until(EC.visibility_of_element_located((By.ID,'connect-wallet')))
conectarCarteira.click()

#Verificação de batalha disponível
status = wait.until(EC.presence_of_element_located((By.CSS_SELECTOR,'#purchased-characters > div.center > div:nth-child(2) > div > img')))
status = status.get_attribute("src")

if "Descansando" in status:
    print("Personagem em tempo de espera")
    
    #Enviando log
    values = ('',horaAtual,"",'Personagem em tempo de espera')
    cursor.execute(sql,values)
    cursor.close()
    connection.commit()
    connection.close()
    
    time.sleep(5)
    driver.quit()
else:
    print("Personagem pronto para a batalha")
    #Batalhar
    batalhar = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR,'#purchased-characters > div.center > div:nth-child(2) > a')))
    batalhar.click()
    
    #Confirmar pop-up 
    wait.until(EC.alert_is_present())
    Alert(driver).accept()
    time.sleep(5)
    
    #Confirmar transação na MetaMask
    driver.switch_to.window(driver.window_handles[0])
    driver.execute_script("location.reload();") 
    confirmarTransação = wait.until(EC.presence_of_element_located((By.CSS_SELECTOR,'#app-content > div > div.main-container-wrapper > div > div.confirm-page-container-content.confirm-page-container-content--with-top-border > div.page-container__footer > footer > button.button.btn--rounded.btn-primary.page-container__footer-button')))
    confirmarTransação.click()
    
    print("Batalha realizada com sucesso")
    
    #Enviando log
    values = ('',horaAtual,proxHora,'Batalha realizada com sucesso!')
    cursor.execute(sql,values)
    
    cursor.close()
    connection.commit()
    connection.close()
    
    time.sleep(5)
    driver.quit()

