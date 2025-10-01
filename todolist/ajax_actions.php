<?php
// Incluir configuração e classe
include_once 'config.php';
include_once 'Tarefa.php';

// Conectar ao banco
$database = new Database();
$db = $database->getConnection();
$tarefa = new Tarefa($db);

$action = $_REQUEST['action'] ?? '';

switch($action) {
    case 'criar':
        header('Content-Type: application/json');
        if($_POST) {
            $tarefa->titulo = $_POST['titulo'];
            $tarefa->descricao = $_POST['descricao'];
            
            if($tarefa->criar()) {
                echo json_encode(array('status' => 'success', 'message' => 'Tarefa criada com sucesso.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Erro ao criar tarefa.'));
            }
        }
        break;

    case 'listar':
        $stmt = $tarefa->listar();
        $num = $stmt->rowCount();
        
        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                
                $classConcluida = $concluida ? 'task-completed' : '';
                $textConcluida = $concluida ? 'Desmarcar' : 'Concluir';
                $iconConcluida = $concluida ? 'fa-undo' : 'fa-check';
                $badgeConcluida = $concluida ? 
                    '<span class="badge bg-success"><i class="fas fa-check"></i> Concluída</span>' : 
                    '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pendente</span>';
                
                echo "
                <div class='card task-item mb-3 {$classConcluida}'>
                    <div class='card-body'>
                        <div class='row align-items-center'>
                            <div class='col-md-8'>
                                <h5 class='card-title mb-1'>{$titulo} {$badgeConcluida}</h5>
                                " . ($descricao ? "<p class='card-text text-muted mb-1'>{$descricao}</p>" : "") . "
                                <small class='text-muted'>
                                    <i class='fas fa-calendar'></i> Criada em: " . date('d/m/Y H:i', strtotime($data_criacao)) . "
                                    " . ($data_criacao != $data_atualizacao ? 
                                    " | <i class='fas fa-edit'></i> Atualizada: " . date('d/m/Y H:i', strtotime($data_atualizacao)) : "") . "
                                </small>
                            </div>
                            <div class='col-md-4 text-end'>
                                <button class='btn " . ($concluida ? 'btn-warning' : 'btn-success') . " btn-concluir' data-id='{$id}'>
                                    <i class='fas {$iconConcluida}'></i> {$textConcluida}
                                </button>
                                <button class='btn btn-danger btn-excluir' data-id='{$id}'>
                                    <i class='fas fa-trash'></i> Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            
            // Estatísticas rápidas
            $estatisticas = $tarefa->getEstatisticas();
            echo "
            <div class='row mt-3'>
                <div class='col-12'>
                    <div class='alert alert-info'>
                        <div class='row text-center'>
                            <div class='col-md-3'>
                                <strong>Total:</strong> {$estatisticas['total']}
                            </div>
                            <div class='col-md-3'>
                                <strong>Concluídas:</strong> {$estatisticas['concluidas']}
                            </div>
                            <div class='col-md-3'>
                                <strong>Pendentes:</strong> {$estatisticas['pendentes']}
                            </div>
                            <div class='col-md-3'>
                                <strong>Progresso:</strong> {$estatisticas['percentual']}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
        } else {
            echo "
            <div class='alert alert-info text-center py-4'>
                <i class='fas fa-info-circle fa-3x mb-3 text-info'></i>
                <h4>Nenhuma tarefa encontrada</h4>
                <p class='mb-0'>Adicione sua primeira tarefa usando o formulário acima.</p>
            </div>";
        }
        break;

    case 'marcarConcluida':
        header('Content-Type: application/json');
        if($_POST) {
            $tarefa->id = $_POST['id'];
            
            if($tarefa->marcarConcluida()) {
                echo json_encode(array('status' => 'success', 'message' => 'Status da tarefa atualizado.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Erro ao atualizar tarefa.'));
            }
        }
        break;

    case 'excluir':
        header('Content-Type: application/json');
        if($_POST) {
            $tarefa->id = $_POST['id'];
            
            if($tarefa->excluir()) {
                echo json_encode(array('status' => 'success', 'message' => 'Tarefa excluída com sucesso.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Erro ao excluir tarefa.'));
            }
        }
        break;

    case 'visualizarTabela':
        try {
            // Query para obter todos os dados da tabela
            $query = "SELECT * FROM tarefas ORDER BY data_criacao DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            $num = $stmt->rowCount();
            
            if($num > 0) {
                echo "<table class='table table-bordered table-striped table-hover'>";
                echo "<thead class='thead-dark'>";
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Título</th>";
                echo "<th>Descrição</th>";
                echo "<th>Concluída</th>";
                echo "<th>Data Criação</th>";
                echo "<th>Data Atualização</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    
                    // Formatar valores booleanos
                    $concluida_formatada = $concluida ? 
                        '<span class="badge bg-success">Sim</span>' : 
                        '<span class="badge bg-warning">Não</span>';
                    
                    // Formatar datas
                    $data_criacao_formatada = date('d/m/Y H:i:s', strtotime($data_criacao));
                    $data_atualizacao_formatada = date('d/m/Y H:i:s', strtotime($data_atualizacao));
                    
                    echo "<tr>";
                    echo "<td><strong>{$id}</strong></td>";
                    echo "<td>{$titulo}</td>";
                    echo "<td>" . ($descricao ?: '<em class="text-muted">Sem descrição</em>') . "</td>";
                    echo "<td class='text-center'>{$concluida_formatada}</td>";
                    echo "<td>{$data_criacao_formatada}</td>";
                    echo "<td>{$data_atualizacao_formatada}</td>";
                    echo "</tr>";
                }
                
                echo "</tbody>";
                echo "</table>";
                
                // Estatísticas detalhadas
                $estatisticas = $tarefa->getEstatisticas();
                echo "
                <div class='row mt-4'>
                    <div class='col-md-3'>
                        <div class='stats-card'>
                            <i class='fas fa-tasks'></i>
                            <h4>{$estatisticas['total']}</h4>
                            <p>Total de Tarefas</p>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='stats-card' style='background: linear-gradient(45deg, #28a745, #20c997);'>
                            <i class='fas fa-check-circle'></i>
                            <h4>{$estatisticas['concluidas']}</h4>
                            <p>Concluídas</p>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='stats-card' style='background: linear-gradient(45deg, #ffc107, #fd7e14);'>
                            <i class='fas fa-clock'></i>
                            <h4>{$estatisticas['pendentes']}</h4>
                            <p>Pendentes</p>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='stats-card' style='background: linear-gradient(45deg, #17a2b8, #6f42c1);'>
                            <i class='fas fa-chart-line'></i>
                            <h4>{$estatisticas['percentual']}%</h4>
                            <p>Taxa de Conclusão</p>
                        </div>
                    </div>
                </div>";
                
            } else {
                echo "
                <div class='alert alert-warning text-center py-4'>
                    <i class='fas fa-database fa-3x mb-3'></i>
                    <h4>Tabela Vazia</h4>
                    <p class='mb-0'>A tabela 'tarefas' não contém nenhum registro.</p>
                </div>";
            }
            
        } catch(PDOException $exception) {
            echo "
            <div class='alert alert-danger'>
                <h5><i class='fas fa-exclamation-triangle'></i> Erro ao acessar o banco de dados:</h5>
                <p>" . $exception->getMessage() . "</p>
                <small>Verifique se a tabela 'tarefas' existe no banco de dados.</small>
            </div>";
        }
        break;

    default:
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'error', 'message' => 'Ação não reconhecida.'));
        break;
}
?>