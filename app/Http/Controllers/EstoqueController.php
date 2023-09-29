<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function tabela()
    {
        //..recupera o veículo da base de dados
        $estoque = Estoque::all('id', 'id_produto', 'produto', 'validade', 'qtde');
        // return response()->json($estoque);
        return json_encode($estoque);
    }

    /**
     * Função para atualizar produto
     */
    public function Atualiza_prod(Request $request)
    {
        $dados = $_GET;
        if ($dados['id_produto'] != '' && $dados['produto'] != '' && $dados['qtde'] != '' && $dados['validade'] != '') {

            $ValidId = DB::table('estoques')->where('produto', trim(mb_strtoupper($dados['produto'])))->where('id_produto', '<>', $dados['id_produto'])->select('*')->get();

            if (count($ValidId) > 0) {
                $result = array(
                    "cod" => 0,
                    "mensagem" => 'Produto já cadastrado'
                );
            } else {

                $affected = DB::table('estoques')->where('id_produto', $dados['id_produto'])->update(['estoques.produto' => trim(mb_strtoupper($dados['produto'])), 'estoques.qtde' => $dados['qtde'], 'estoques.validade' => $dados['validade']]);
                $result = array(
                    "cod" => 1,
                    "result" => $affected
                );
            }
        } else {
            $result = array(
                "cod" => 0,
                "mensagem" => 'Favor preencher os campos vazios'
            );
        }

        return json_encode($result);
    }

    /**
     * Função para cadastrar produto
     */
    public function cadastrar_prod()
    {
        $dados = $_GET;
        if ($dados['id_produto'] != '' && $dados['produto'] != '' && $dados['qtde'] != '' && $dados['validade'] != '') {

            $ValidId = DB::table('estoques')->where('produto', trim(mb_strtoupper($dados['produto'])))->select('*')->get();

            if (count($ValidId) > 0) {
                $result = array(
                    "cod" => 0,
                    "mensagem" => 'Produto já cadastrado'
                );
            } else {

                $ValidId = DB::table('estoques')->where('id_produto', trim($dados['id_produto']))->select('*')->get();

                if (count($ValidId) > 0) {
                    $result = array(
                        "cod" => 0,
                        "mensagem" => 'Código já cadastrado'
                    );
                } else {
                    $affected = DB::table('estoques')->insert([
                        ['id_produto' => $dados['id_produto'], 'produto' => trim(mb_strtoupper($dados['produto'])), 'qtde' => $dados['qtde'], 'validade' => $dados['validade']]
                    ]);

                    $result = array(
                        "cod" => 1,
                        "result" => $affected
                    );
                }
            }
        } else {
            $result = array(
                "cod" => 0,
                "mensagem" => 'Favor preencher os campos vazios'
            );
        }
        return json_encode($result);
    }

    /**
     * Função para deletar produto
     */
    public function deletar_prod()
    {
        $dados = $_GET;
        $affected = DB::table('estoques')->where('id_produto', $dados['id'])->delete();
        return $affected;
    }
}
