Aplicação desenvolvida em python utilizando a biblioteca Selenium e conexão a banco de dados mysql.

O objetivo da aplicação é utilizar um bot para acessar a carteira da metamask e logo em seguida acessar o projeto Danki Castle e logar na conta com um NFT ativo e executar a batalha. (Funcionamento do sistema de batalhas explicado no README inicial do repositório). Após a execução do código a aplicação também grava em um banco de dados o resultado da aplicação, gerando assim um log de execução com o resultado, a hora da operação e a próxima hora que a batalha estará disponível. 

*Observações:
Para execução do código é necessário pré-configurar o browser com os dados da metamask (os dados do browser ficam salvos após a execução numa pasta que pode ser selecionada no código) e ter uma database no formato (id,hora,proxima_hora,status)
