import puppeteer from 'puppeteer';
import dappeteer  from '@chainsafe/dappeteer';
import dns from 'dns';

function delay(time){
    return new Promise(function (resolve){
        setTimeout(resolve,time);
    })
}

async function main() {
    //Configurando Metamask
    const browser = await dappeteer.launch(puppeteer, { metamaskVersion: 'v10.8.1', headless:false});
    const metamask = await dappeteer.setupMetamask(browser,{seed:"Aqui vai sua chave da carteira metamask"});
    await metamask.addNetwork({networkName: "BSC",rpc: "https://bsc-dataseed.binance.org/",chainId: "56",symbol: "BNB",explorer:"https://bscscan.com"})
    await metamask.switchNetwork('BSC')

    //Abrindo navegador
    const dankicastle = await browser.newPage();
    await dankicastle.goto('https://app.dankicastle.io/')

    //Conectar carteira
    await dankicastle.waitForSelector("#connect-wallet")
    delay(1000)
    const conectarCarteira = await dankicastle.$("#connect-wallet")
    await conectarCarteira.click()
    await metamask.approve()
    delay(1000)

    //Retorna ao Danki Castle
    await dankicastle.bringToFront()

    //Batalhar 
    await dankicastle.waitForSelector("#purchased-characters > div.center > div:nth-child(2) > a")
    delay(1000)
    const batalhar = await dankicastle.$("#purchased-characters > div.center > div:nth-child(2) > a")

    const verificaBatalha = await dankicastle.evaluate(()=>{
        const verificaBatalha = document.querySelector("#purchased-characters > div.center > div:nth-child(2) > a")
        return verificaBatalha.innerHTML 
    })

    if (verificaBatalha.includes("Exausto")){
        console.log("Personagem em tempo de espera")
        delay(1000)
        await browser.close()
    }else{
        console.log("Personagem pronto para batalha")
        dankicastle.on('dialog',async (dialog) => {
            await dialog.accept()
        })
        await batalhar.click()
        await delay(500) // 500 funcionou, porém pode ser q este valor oscile de acordo com conexão à internet ou processamento do computador   
        await metamask.confirmTransaction()
        await delay(2000)
       
        console.log("Batalha realizada com sucesso!")
        await browser.close()
    }
}

//Verifica a conexão à internet antes de executar o main
dns.resolve('www.google.com', function(err) {
    if (err) {
       console.log("Sem conexão");
    } else {
       console.log("Conectado");
       main()
    }
});