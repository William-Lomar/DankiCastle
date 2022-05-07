Projeto desenvolvido em NodeJS utilizando as bibliotecas puppeteer e dappeteer.

O projeto foi descontinuado pois apresentava problemas e falhas de desempenho. (Acredito ser por causa do funcionamento da biblioteca dappeteer)   

O objetivo da aplicação é utilizar um bot para acessar a carteira da metamask e logo em seguida acessar o projeto Danki Castle e logar na conta com um NFT ativo e executar a batalha. (Funcionamento do sistema de batalhas explicado no README inicial do repositório)

Acredito que as falhas ocorriam devido ao Chromium (browser utilizado pelo dappeteer), pois o mesmo não guardava nenhuma informação após a execução do código, assim a cada execução é necessário realizar toda a configuração da carteira metamask, outro problema é que as solicitações enviadas para metamask são recusadas automaticamente pelo Chromium, porém o código consegue aceitar a solicitação antes que o navegador a recuse, caso seja executado numa janela de tempo muito especifica, ocasionando erros intermitentes durante sua execução.

Como não foi possível atingir o objetivo por meio dessa aplicação foi realizado pesquisas de maneiras alternativas para realizar tal tarefa, sendo que uma solução efetiva foi a utilização de bots utilizando a biblioteca Selenium do python conforme pode-se observar no repositório a seguir: https://github.com/William-Lomar/DankiCastle/tree/main/bot_python





