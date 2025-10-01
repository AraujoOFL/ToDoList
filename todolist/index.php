<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Lista de Tarefas</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AdminLTE Style Inspiration -->
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
        }
        .content-wrapper {
            background-color: #f4f6f9;
            min-height: 100vh;
        }
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
            border: none;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 0.75rem 1.25rem;
            position: relative;
        }
        .card-header .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .task-completed {
            text-decoration: line-through;
            color: #6c757d;
        }
        .task-item {
            border-left: 3px solid #007bff;
            transition: all 0.3s;
        }
        .task-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .task-completed .task-item {
            border-left-color: #28a745;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }
        .stats-card {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .stats-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .card-tools .btn {
            margin-left: 5px;
        }
        .badge {
            font-size: 0.75em;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tasks mr-2"></i>
                                Sistema de Lista de Tarefas
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Formulário para adicionar nova tarefa -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <form id="formTarefa">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="titulo" class="form-label">Título da Tarefa *</label>
                                                    <input type="text" class="form-control" id="titulo" name="titulo" required 
                                                           placeholder="Digite o título da tarefa">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="descricao" class="form-label">Descrição</label>
                                                    <input type="text" class="form-control" id="descricao" name="descricao"
                                                           placeholder="Digite a descrição (opcional)">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="submit" class="btn btn-success btn-block w-100">
                                                        <i class="fas fa-plus"></i> Adicionar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Lista de Tarefas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <i class="fas fa-list mr-2"></i>
                                                Lista de Tarefas
                                            </h4>
                                            <div class="card-tools">
                                                <button class="btn btn-sm btn-outline-secondary" onclick="carregarTarefas()">
                                                    <i class="fas fa-sync-alt"></i> Atualizar
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="listaTarefas">
                                                <!-- As tarefas serão carregadas aqui via Ajax -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visualização da Tabela -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-table mr-2"></i>
                                Visualização da Tabela do Banco de Dados
                            </h3>
                            <div class="card-tools">
                                <button class="btn btn-sm btn-info" onclick="carregarTabela()">
                                    <i class="fas fa-sync-alt"></i> Atualizar
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="visualizacaoTabela">
                                    <!-- Conteúdo da tabela será carregado aqui -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        // Carregar tarefas ao iniciar
        carregarTarefas();
        carregarTabela();

        // Adicionar nova tarefa
        $('#formTarefa').on('submit', function(e) {
            e.preventDefault();
            
            var titulo = $('#titulo').val();
            var descricao = $('#descricao').val();

            if(titulo.trim() === '') {
                alert('Por favor, insira um título para a tarefa.');
                return;
            }

            $.ajax({
                url: 'ajax_actions.php',
                type: 'POST',
                data: {
                    action: 'criar',
                    titulo: titulo,
                    descricao: descricao
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if(result.status === 'success') {
                        $('#titulo').val('');
                        $('#descricao').val('');
                        carregarTarefas();
                        carregarTabela();
                    } else {
                        alert('Erro ao adicionar tarefa: ' + result.message);
                    }
                },
                error: function() {
                    alert('Erro de comunicação com o servidor.');
                }
            });
        });

        // Função para carregar tarefas
        function carregarTarefas() {
            $.ajax({
                url: 'ajax_actions.php',
                type: 'GET',
                data: { action: 'listar' },
                success: function(response) {
                    $('#listaTarefas').html(response);
                },
                error: function() {
                    $('#listaTarefas').html(
                        '<div class="alert alert-danger">Erro ao carregar tarefas.</div>'
                    );
                }
            });
        }

        // Função para carregar visualização da tabela
        function carregarTabela() {
            $.ajax({
                url: 'ajax_actions.php',
                type: 'GET',
                data: { action: 'visualizarTabela' },
                success: function(response) {
                    $('#visualizacaoTabela').html(response);
                },
                error: function() {
                    $('#visualizacaoTabela').html(
                        '<div class="alert alert-danger">Erro ao carregar dados da tabela.</div>'
                    );
                }
            });
        }

        // Marcar tarefa como concluída (delegação de evento)
        $(document).on('click', '.btn-concluir', function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: 'ajax_actions.php',
                type: 'POST',
                data: {
                    action: 'marcarConcluida',
                    id: id
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if(result.status === 'success') {
                        carregarTarefas();
                        carregarTabela();
                    }
                }
            });
        });

        // Excluir tarefa (delegação de evento)
        $(document).on('click', '.btn-excluir', function() {
            var id = $(this).data('id');
            
            if(confirm('Tem certeza que deseja excluir esta tarefa?')) {
                $.ajax({
                    url: 'ajax_actions.php',
                    type: 'POST',
                    data: {
                        action: 'excluir',
                        id: id
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if(result.status === 'success') {
                            carregarTarefas();
                            carregarTabela();
                        }
                    }
                });
            }
        });
    });
    </script>
</body>
</html>