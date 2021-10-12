<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * A class for Banco Inter API
 * @author Katz Soluções Web / Alex Bueno
 * @version 0.1
 * @access public
 */

class Inter
{

    private $parametros;

    public function __construct()
    {
        $this->conta = '0000000000'; // numero da conta (somente numeros com digito)
        $this->cnpj_conta = '000000000000000'; // CNPJ titular da conta

        $this->senha = '########'; // senha da chave da api

        $this->parametros = array(
            'accept: application/json',
            'Content-type: application/json',
            'x-inter-conta-corrente: ' . $this->conta,
        );
    }

    public function debugConexao()
    {

        /* função para testar se as configurações estavam corretas
        /* para conexão exibindo as respostas da api */

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Primeira opção
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Segunda opção
        curl_setopt($curl, CURLOPT_URL, "https://apis.bancointer.com.br/openbanking/v1/certificado/boletos?filtrarPor=TODOS&dataInicial=2020-01-01&dataFinal=2023-08-01");
        curl_setopt($curl, CURLOPT_PORT, 8443);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0); //retorna o cabeçalho de resposta
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_SSLCERT, 'publicchave.pem'); // chave na raiz no projeto
        curl_setopt($curl, CURLOPT_SSLKEY, 'private-chave.pem'); // chave na raiz no projeto
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->senha);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parametros);

        $resposta = curl_exec($curl);
        $info = curl_errno($curl) > 0 ? array("curlerror" . curl_errno($curl) => curl_error($curl)) : curl_getinfo($curl);
        print_r($info);
        print_r($resposta);
        print_r($curl);
        //return $info;
    }

    public function testarConexao()
    {
        /* função usada como teste de conexão antes de chamar a geração de boletos */

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Primeira opção
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Segunda opção
        curl_setopt($curl, CURLOPT_URL, "https://apis.bancointer.com.br/openbanking/v1/certificado/boletos?filtrarPor=TODOS&dataInicial=2020-01-01&dataFinal=2020-08-01");
        curl_setopt($curl, CURLOPT_PORT, 8443);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0); //retorna o cabeçalho de resposta
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_SSLCERT, 'publicchave.pem');
        curl_setopt($curl, CURLOPT_SSLKEY, 'private-chave.pem');
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->senha);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parametros);

        $resposta = curl_exec($curl);
        $info = curl_errno($curl) > 0 ? array("curlerror" . curl_errno($curl) => curl_error($curl)) : curl_getinfo($curl);
        if (curl_errno($curl) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function gerarBoleto($dados)
    {
        /* função para gerar um boleto seguindo as regras da api, ajuste os dados de acordo com o que preferir
        /* seuNumero = numero interno para controle (pedido, fatura, etc)
         */
        $dadosBoleto = '
        {
        "pagador":{
        "cnpjCpf":"' . $dados['cpf_cnpj_cliente'] . '",
        "nome":"' . $this->corrigeNome($dados['nome']) . '",
        "email":"' . $dados['email'] . '",
        "telefone":"",
        "cep":"' . $dados['cep'] . '",
        "numero":"' . $dados['numero'] . '",
        "complemento":"' . $dados['complemento'] . '",
        "bairro":"' . $dados['bairro'] . '",
        "cidade":"' . $dados['cidade'] . '",
        "uf":"' . $dados['uf'] . '",
        "endereco":"' . $dados['endereco'] . '",
        "ddd":"42",
        "tipoPessoa":"' . $dados['tipoPessoa'] . '"
        },
        "dataEmissao":"' . $dados['emissao'] . '",
        "seuNumero":"' . $dados['seuNumero'] . '",
        "dataLimite":"TRINTA",
        "dataVencimento":"' . $dados['vencimento'] . '",
        "mensagem":{"linha1":"Mensagem impressa no boleto 1","linha2":"Mensagem impressa no boleto 2","linha3":"Mensagem impressa no boleto 3"
        },
        "desconto1":{
        "codigoDesconto":"NAOTEMDESCONTO",
        "taxa":0,
        "valor":0,
        "data":""
        },
        "desconto2":{
        "codigoDesconto":"NAOTEMDESCONTO",
        "taxa":0,
        "valor":0,
        "data":""
        },
        "desconto3":{
        "codigoDesconto":"NAOTEMDESCONTO",
        "taxa":0,
        "valor":0,
        "data":""
        },
        "valorNominal":' . $dados['valor'] . ',
        "valorAbatimento":0,
        "multa":{
        "codigoMulta":"NAOTEMMULTA",
        "valor":0,
        "taxa":0
        },
        "mora":{
        "codigoMora":"ISENTO",
        "valor":0,
        "taxa":0
        }
        ,
        "cnpjCPFBeneficiario":"' . $this->cnpj_conta . '",
        "numDiasAgenda":"SESSENTA"
        }"';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, "https://apis.bancointer.com.br/openbanking/v1/certificado/boletos");
        curl_setopt($curl, CURLOPT_PORT, 8443);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSLCERT, 'publicchave.pem');
        curl_setopt($curl, CURLOPT_SSLKEY, 'private-chave.pem');
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->senha);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parametros);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dadosBoleto);
        $resposta = curl_exec($curl);
        $info = curl_errno($curl) > 0 ? array("curlerror" . curl_errno($curl) => curl_error($curl)) : curl_getinfo($curl);
        /*print_r($dadosBoleto);
        print_r($resposta);
        exit;*/
        curl_close($curl);
        return json_decode($resposta, true);
    }

    public function listarBoletos()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Primeira opção
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Segunda opção
        curl_setopt($curl, CURLOPT_URL, "https://apis.bancointer.com.br/openbanking/v1/certificado/boletos?filtrarPor=TODOS&dataInicial=2020-01-01&dataFinal=2025-08-01");
        curl_setopt($curl, CURLOPT_PORT, 8443);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0); //retorna o cabeçalho de resposta
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_SSLCERT, 'publicchave.pem');
        curl_setopt($curl, CURLOPT_SSLKEY, 'private-chave.pem');
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->senha);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parametros);

        $resposta = curl_exec($curl);
        $info = curl_errno($curl) > 0 ? array("curlerror" . curl_errno($curl) => curl_error($curl)) : curl_getinfo($curl);
        curl_close($curl);
        return json_decode($resposta, true);
    }

    public function listarBoleto($numeroBoleto)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Primeira opção
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Segunda opção
        curl_setopt($curl, CURLOPT_URL, "https://apis.bancointer.com.br/openbanking/v1/certificado/boletos/$numeroBoleto");
        curl_setopt($curl, CURLOPT_PORT, 8443);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0); //retorna o cabeçalho de resposta
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_SSLCERT, 'publicchave.pem');
        curl_setopt($curl, CURLOPT_SSLKEY, 'private-chave.pem');
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->senha);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parametros);

        $resposta = curl_exec($curl);
        $info = curl_errno($curl) > 0 ? array("curlerror" . curl_errno($curl) => curl_error($curl)) : curl_getinfo($curl);
        curl_close($curl);
        return json_decode($resposta, true);
    }

    public function listarBoletoPdf($numeroBoleto)
    {
        $this->parametros = array(
            'accept: application/pdf',
            'Content-type: application/pdf',
            'x-inter-conta-corrente: ' . $this->conta,
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, "https://apis.bancointer.com.br/openbanking/v1/certificado/boletos/$numeroBoleto/pdf");
        curl_setopt($curl, CURLOPT_PORT, 8443);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_SSLCERT, 'publicchave.pem');
        curl_setopt($curl, CURLOPT_SSLKEY, 'private-chave.pem');
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->senha);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parametros);

        $resposta = curl_exec($curl);
        $info = curl_errno($curl) > 0 ? array("curlerror" . curl_errno($curl) => curl_error($curl)) : curl_getinfo($curl);
        //print_r($info);
        //print_r($resposta);
        curl_close($curl);
        return base64_decode($resposta);
    }

    public function baixaBoleto($numeroBoleto)
    {
        $dadosBoleto = '{"codigoBaixa":"PAGODIRETOAOCLIENTE"}'; // se preferir mude o motivo da baixa, conferir na api as opções
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, "https://apis.bancointer.com.br/openbanking/v1/certificado/boletos/$numeroBoleto/baixas");
        curl_setopt($curl, CURLOPT_PORT, 8443);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSLCERT, 'publicchave.pem');
        curl_setopt($curl, CURLOPT_SSLKEY, 'private-chave.pem');
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->senha);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parametros);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dadosBoleto);

        $resposta = curl_exec($curl);
        $info = curl_errno($curl) > 0 ? array("curlerror" . curl_errno($curl) => curl_error($curl)) : curl_getinfo($curl);
        /* print_r($info);
        print_r('RESPOSTA');
        print_r($resposta); */
        curl_close($curl);
        return base64_decode($resposta);
    }

    public function corrigeNome($nome)
    {
        /* tira caracteres invalidos e padroniza o nome para envio */

        $nomeLimpo = str_replace('/', '-', $nome);
        $nomeLimpo = str_replace('&', 'e', $nomeLimpo);
        $nomeLimpo = str_replace('+', '-', $nomeLimpo);
        $nomeLimpo = str_replace('.', '', $nomeLimpo);
        $nomeLimpo = str_replace('(', '', $nomeLimpo);
        $nomeLimpo = str_replace(')', '', $nomeLimpo);
        $nomeLimpo = strtolower($nomeLimpo);
        $nomeLimpo = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $nomeLimpo);
        return ucwords($nomeLimpo);
    }
}