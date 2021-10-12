# CodeIgniter PHP / API Boletos Conta PJ Banco Inter

## 
<h1 align="center">
    <a href="https://www.bancointer.com.br/empresas/conta-digital/pessoa-juridica/">🔗 CodeIgniter PHP / API Boletos Conta PJ Banco Inter</a>
</h1>
<p align="center">🚀 Biblioteca (lib) para CodeIgniter 3 exemplificando o gerenciamento de boletos de contas PJ no Banco Inter.
</p>

### Features

- [x] Gerar Boleto
- [x] Consultar Boleto
- [x] Imprimir PDF Boleto
- [x] Fazer Baixa Manual de Boleto

### Pré-requisitos

A biblioteca é voltada para uso em Codeigniter 3, mas você pode adequar e utilizar da forma que preferir em PHP puro ou outro Framework como Laravel, a idéia é a mesma. Para visualização do projeto é bom ter um editor para trabalhar com o código como [VSCode](https://code.visualstudio.com/)

### 🎲 Utilizando a biblioteca

- Baixe o repositorio e insira os arquivos nas respctivas pastas do seu projeto em CodeIgniter 3.
- Baixa o certificado diretamente na seção APIs no internet banking utilizando a conta PJ, guarde a senha utilizada na assinatura dele pois vai precisar.
- Faça a conversão do certificado .crt para utilização no PHP gerando atraves do certificado baixado do site.

- Rode os seguintes comandos via terminal no servidor onde estará hospedado seu sistema (voce pode testar localhost)

```bash
# Transforme o certificado .crt em uma chave public e outra private no formato .pem
$ openssl x509 -inform PEM -in seu_certificado.crt > publicchave.pem
$ openssl rsa -in urldosistema.com.br.key -text > private-chave.pem
```
- Faça as adequações necessárias no código para utilização em seu sistema e se divirta!


### 🛠 Tecnologias

As seguintes ferramentas foram usadas:

- [CodeIgniter 3](https://codeigniter.com/download)

### Autor
---
<a href="https://www.katzweb.com.br">
 <img style="border-radius: 50%;" style="height:auto;" alt="" class="avatar avatar-user width-full border color-bg-primary" src="https://avatars.githubusercontent.com/u/62678401?v=4" width="100" height="100"/>
 <br />
 <sub><b>Alex Bueno / Katz Web</b></sub></a> <a href="https://www.katzweb.com.br" title="Alex Bueno">🐱</a>


Feito com ❤️ por Alex Bueno 👋🏽 Entre em contato!

Com contribuições do pessoal do fórum de desenvolvedores do [Banco Inter](https://developers.bancointer.com.br/reference).
Agradecimento especial ao Edmilson Orlando de Oliveira que criou um exemplo em PHP puro.

https://img.shields.io/github/issues/alexbueno7/banco-inter-api-codeigniter
https://img.shields.io/github/forks/alexbueno7/banco-inter-api-codeigniter
https://img.shields.io/github/stars/alexbueno7/banco-inter-api-codeigniter
https://img.shields.io/twitter/url?url=https%3A%2F%2Fgithub.com%2Falexbueno7%2Fbanco-inter-api-codeigniter