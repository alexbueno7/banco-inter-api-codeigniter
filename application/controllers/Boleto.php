<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Boleto extends MY_Controller
{

    public function gerar()
    {
        /* dadosFatura é carregado a partir da base de dados, substitua pelos dados do seu sistema */

        $dadosBoleto = array();
        $dadosBoleto['cpf_cnpj_cliente'] = preg_replace("/[^0-9]/", "", $dadosFatura->cpf_cnpj_cliente);
        $dadosBoleto['nome'] = ucwords(strtolower($dadosFatura->nome_cliente));
        $dadosBoleto['email'] = strtolower($dadosFatura->email);
        $dadosBoleto['cep'] = preg_replace("/[^0-9]/", "", $dadosFatura->cep);
        $dadosBoleto['endereco'] = ucwords(strtolower($dadosFatura->logradouro));
        $dadosBoleto['numero'] = preg_replace("/[^0-9]/", "", $dadosFatura->numero);
        $dadosBoleto['complemento'] = ucwords(strtolower($dadosFatura->complemento));
        $dadosBoleto['bairro'] = ucwords(strtolower($dadosFatura->bairro));
        $dadosBoleto['cidade'] = ucwords(strtolower($dadosFatura->cidade));
        $dadosBoleto['uf'] = strtoupper($dadosFatura->estado);
        $dadosBoleto['ddd'] = "11"; // é obrigatorio enviar um DDD
        $dadosBoleto['telefone'] = "999999999"; // é obrigatorio enviar um telefone
        $dadosBoleto['tipoPessoa'] = strtoupper(($dadosFatura->tp_pessoa == 1 ? 'FISICA' : 'JURIDICA'));
        $dadosBoleto['emissao'] = date("Y-m-d");
        $dadosBoleto['seuNumero'] = $dadosFatura->id_fatura;
        $dadosBoleto['vencimento'] = $dadosFatura->duedate;
        $dadosBoleto['valor'] = $dadosFatura->total;

        $this->load->library('inter');
        if ($this->inter->testarConexao()) { // se a conexão com a API estiver ok segue
            if ($retornoBoleto = $this->inter->gerarBoleto($dadosBoleto)) { // gera o boleto com os dados enviados
                if (!isset($retornoBoleto['nossoNumero'])) { // valida alguma variável do retorno para confirmar a geração do boleto
                    print_r('Houve um problema no comunicação com o sistema do Banco!<br>Tente novamente em alguns minutos, entre em contato se o problema persistir!<br>Abaixo mais detalhes:');
                    print_r($retornoBoleto);exit; // mostra o retorno com algum erro que tenha acontecido
                } else {
                    print_r($retornoBoleto);exit; // boleto gerado ocm sucesso!
                }
            }
        } else {
            print_r('Houve um problema no comunicação com o sistema do Banco! Abaixo mais detalhes:');
            exit;
        }
    }

    public function visualizarBoleto($numeroBoleto)
    {
        $this->load->library('inter');
        if ($this->inter->testarConexao()) {
            $boletoPdf = $this->inter->listarBoletoPdf($numeroBoleto);
            header('Content-type:application/pdf');
            header('Content-Disposition: attachment; filename=Katz-Boleto-' . $numeroBoleto . '.pdf');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            echo $boletoPdf;
            exit;
        } else {
            print_r('Houve um problema na comunicação com o sistema do Banco!<br>Tente novamente em alguns minutos, entre em contato se o problema persistir!');
        }
    }

    public function confirmaPagamento($numeroBoleto)
    {
        $this->load->library('inter');
        $boleto = $this->inter->listarBoleto($numeroBoleto);
        if ($boleto['situacao'] === 'PAGO') {
            // regras de negocio para o boleto pago
        } else {
            // regras de negocio para o boleto pendente
        }
    }

    public function baixaManual($numeroBoleto)
    {
        $this->inter->baixaBoleto($numeroBoleto);
    }
}