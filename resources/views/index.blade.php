@extends('layout')

@section('content')

<div class="container">
    <div id="toolbar">
        <button class="btn btn-outline-primary" data-toggle="modal" onclick="modalCadProd()">CADASTRO DE PRODUTO</button>
    </div>
    <div class="row">
        <div class="col-12">
            <table id="table" data-toggle="table" class="table" data-toolbar="#toolbar" data-show-columns-toggle-all="true" data-pagination="true" data-search="true">
                <thead>
                    <tr>
                        <th data-halign="center" data-align="center" data-field="id">ID</th>
                        <th data-halign="center" data-align="right" data-field="id_produto">Código do produto</th>
                        <th data-halign="center" data-align="center" data-field="produto">Produto</th>
                        <th data-halign="center" data-align="right" data-field="qtde">Qtde</th>
                        <th data-halign="center" data-align="center" data-field="validade" data-formatter="data">Validade</th>
                        <th data-halign="center" data-align="center" data-field="id_produto" data-formatter="editar">Edit</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="edit_prod" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="htexto"></h5>
                <button type="button" class="btn btn-dark" onclick="$('#edit_prod').modal('hide');">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">

                    <form id="formProd">
                        <div class="row">
                            <div class="col-3">
                                <label>Código do produto</label>
                                <input type="text" id="id_produto" name="id_produto" class="form-control" readonly>
                            </div>
                            <div class="col-3">
                                <label>Produto</label>
                                <input type="text" id="produto" name="produto" class="form-control">
                            </div>
                            <div class="col-3">
                                <label>Qtde</label>
                                <input type="text" id="qtde" name="qtde" class="form-control">
                            </div>
                            <div class="col-3">
                                <label>Validade</label>
                                <input type="date" id="validade" name="validade" class="form-control" min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="atualizar" onclick="atualizar()">Atualizar</button>
                <button type="button" class="btn btn-primary" id="cadastrar" onclick="cadastrar()">Cadastrar</button>
                <button type="button" class="btn btn-danger" onclick="$('#edit_prod').modal('hide');">Fechar</button>
            </div>
        </div>
    </div>
</div>
</div>


@endsection

<script>
    window.onload = function() {
        tabela();
    }

    function data(value) {
        return moment(value).format('L');;
    }

    function modalCadProd() {
        $('#edit_prod').modal('show');
        $('#id_produto').val('');
        $('#produto').val('');
        $('#qtde').val('');
        $('#validade').val('');
        $('#htexto').text('CADASTRAR PRODUTO');
        $('#cadastrar').removeClass('d-none')
        $('#atualizar').addClass('d-none')
        $("#id_produto").prop('readonly', false);
    }


    function cadastrar() {
        $.ajax({
            url: "http://localhost:8000/cadastrar",
            type: "GET",
            dataType: 'json',
            data: $('#formProd').serialize(),
            beforeSend: function() {
                Swal.fire({
                    title: "Aguarde!",
                    text: "Buscando dados...",
                    imageUrl: '',
                    imageWidth: 100,
                    imageHeight: 80,
                    showConfirmButton: false
                })
            },
            error: function(data_error) {
                Swal.fire("Oops...", "Aguarde alguns minutos antes de tentar novamente!", "info");
            },
            success: function(result) {
                if (result.cod == 1) {
                    // Atualiza tabela
                    $.ajax({
                        url: "http://localhost:8000/tabela",
                        type: "GET",
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                            $("#table").bootstrapTable('removeAll');
                            $("#table").bootstrapTable('append', data);
                        },
                    });
                    $('#edit_prod').modal('hide');
                    Swal.fire('OK!', 'Alteração realizada com sucesso!', 'success')
                } else if (result.cod == 0) {
                    Swal.fire('Ops!', '' + result.mensagem + '', 'info')
                } else {
                    Swal.fire('Ops!', 'Algo deu errado, favor tentar em alguns minutos!', 'info')
                }
            },
        });
    }

    function atualizar() {
        $.ajax({
            url: "http://localhost:8000/Atualiza_prod",
            type: "GET",
            dataType: 'json',
            data: $('#formProd').serialize(),
            beforeSend: function() {
                Swal.fire({
                    title: "Aguarde!",
                    text: "Buscando dados...",
                    imageUrl: '',
                    imageWidth: 100,
                    imageHeight: 80,
                    showConfirmButton: false
                })
            },
            error: function(data_error) {
                Swal.fire("Oops...", "Aguarde alguns minutos antes de tentar novamente!", "info");
            },
            success: function(result) {

                console.log(result);
                console.log('chegou aqui');

                if (result.cod == 1) {
                    // Atualiza tabela
                    $.ajax({
                        url: "http://localhost:8000/tabela",
                        type: "GET",
                        dataType: 'json',
                        success: function(data) {
                            $("#table").bootstrapTable('removeAll');
                            $("#table").bootstrapTable('append', data);
                        },
                    });
                    $('#edit_prod').modal('hide');
                    Swal.fire('OK!', 'Alteração realizada com sucesso!', 'success')
                } else if (result.cod == 0) {
                    Swal.fire('Ops!', '' + result.mensagem + '', 'info')
                } else {
                    Swal.fire('Ops!', 'Algo deu errado, favor tentar em alguns minutos!', 'info')
                }
            },
        });
    }

    function editar(index, row) {
        return '<button type="button" class="btn btn-primary" onclick="modalEdit(\'' + index + '\',\'' + row.produto + '\',\'' + row.qtde + '\',\'' + row.validade + '\');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>\n\
        <button type="button" class="btn btn-danger" onclick="deletar(\'' + index + '\');"><i class="fa fa-trash" aria-hidden="true"></i></button>';
    }

    function deletar(id) {
        $.ajax({
            url: "http://localhost:8000/deletar",
            type: "GET",
            dataType: 'json',
            data: {
                id: id
            },
            beforeSend: function() {
                Swal.fire({
                    title: "Aguarde!",
                    text: "Buscando dados...",
                    imageUrl: '',
                    imageWidth: 100,
                    imageHeight: 80,
                    showConfirmButton: false
                })
            },
            error: function(data_error) {
                Swal.fire("Oops...", "Aguarde alguns minutos antes de tentar novamente!", "info");
            },
            success: function(result) {
                if (result == 1) {
                    // Atualiza tabela
                    $.ajax({
                        url: "http://localhost:8000/tabela",
                        type: "GET",
                        dataType: 'json',
                        success: function(result) {
                            $("#table").bootstrapTable('removeAll');
                            $("#table").bootstrapTable('append', result);
                        },
                    });
                    Swal.fire('OK!', 'Deletado com sucesso!', 'success')
                } else {
                    Swal.fire('Ops!', 'Algo deu errado, favor tentar em alguns minutos!', 'info')
                }
            },
        });
    }

    function modalEdit(id, produto, qtde, validade) {
        $('#edit_prod').modal('show');
        $('#id_produto').val(id);
        $('#produto').val(produto);
        $('#qtde').val(qtde);
        $('#validade').val(validade);
        $('#htexto').text('ATUALIZAR PRODUTO');
        $('#atualizar').removeClass('d-none')
        $('#cadastrar').addClass('d-none')
        $("#id_produto").prop('readonly', true);
    }

    // Popula a tabela no inicio da pagina 
    function tabela() {
        $.ajax({
            url: "http://localhost:8000/tabela",
            type: "GET",
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: "Aguarde!",
                    text: "Buscando dados...",
                    imageUrl: '',
                    imageWidth: 100,
                    imageHeight: 80,
                    showConfirmButton: false
                })
            },
            error: function(data_error) {
                Swal.fire("Oops...", "Aguarde alguns minutos antes de tentar novamente!", "info");
            },
            success: function(result) {
                $("#table").bootstrapTable('removeAll');
                $("#table").bootstrapTable('append', result);

                Swal.fire({
                    timer: 10,
                    title: "Aguarde!",
                    text: "Buscando dados...",
                    imageUrl: '',
                    imageWidth: 100,
                    imageHeight: 80,
                    showConfirmButton: false

                })
            },
        });
    }

</script>