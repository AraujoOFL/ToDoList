#  Sistema de Lista de Tarefas

Um sistema completo de gerenciamento de tarefas desenvolvido em PHP, MySQL e Ajax com interface moderna inspirada no AdminLTE.

## ✨ Funcionalidades

-  Cadastrar tarefas com título e descrição
-  Listar todas as tarefas com design responsivo
-  Marcar/desmarcar tarefas como concluídas
-  Excluir tarefas com confirmação
-  Visualização em tempo real da tabela do banco de dados
-  Estatísticas automáticas de progresso
-  Operações Ajax sem refresh da página
-  Interface responsiva com Bootstrap 5

## 🛠️ Tecnologias Utilizadas

- Backend: PHP 7.4+ com PDO
- Banco de Dados: MySQL 5.7+
- Frontend: Bootstrap 5.1, jQuery, Font Awesome
- Comunicação: Ajax para operações assíncronas
- Design: Inspirado no AdminLTE

## 📦 Instalação

### Pré-requisitos
- Servidor web (Apache, Nginx)
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Extensão PDO habilitada no PHP

### Passo a Passo

1. Clone ou baixe os arquivos

2. Configure o banco de dados:
   Execute o arquivo database.sql no MySQL:
   mysql -u root -p < database.sql

3. Ajuste as configurações do banco:
   Edite o arquivo config.php com suas credenciais:

   private $host = 'localhost';
   private $db_name = 'lista_tarefas';
   private $username = 'seu_usuario';
   private $password = 'sua_senha';

4. Estrutura de arquivos:
   sistema-tarefas/
   ├── config.php          # Configurações do banco
   ├── Tarefa.php          # Classe de modelo
   ├── index.php           # Página principal
   ├── ajax_actions.php    # Endpoints Ajax
   └── database.sql        # Script do banco

5. Acesse o sistema:
   http://localhost/sistema-tarefas/index.php

## 🎯 Como Usar

### Gerenciando Tarefas
1. Adicionar Tarefa: Preencha o título (obrigatório) e descrição (opcional), clique em "Adicionar"
2. Marcar como Concluída: Clique no botão "Concluir" na tarefa desejada
3. Excluir Tarefa: Clique no botão "Excluir" (com confirmação)

### Visualização da Tabela
- A seção inferior mostra todos os dados da tabela tarefas em tempo real
- Inclui estatísticas de progresso e métricas
- Atualize os dados clicando no botão "Atualizar"

## 🗃️ Estrutura do Banco

### Tabela tarefas
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT AUTO_INCREMENT | Chave primária |
| titulo | VARCHAR(255) | Título da tarefa |
| descricao | TEXT | Descrição detalhada |
| concluida | TINYINT(1) | Status (0=Pendente, 1=Concluída) |
| data_criacao | TIMESTAMP | Data de criação |
| data_atualizacao | TIMESTAMP | Data da última atualização |

## 🔧 Desenvolvimento

### Arquivos Principais

- config.php: Configurações de conexão com o banco usando PDO
- Tarefa.php: Classe modelo com métodos CRUD
- index.php: Interface principal com Bootstrap
- ajax_actions.php: Endpoints para operações Ajax

### Métodos da API (Ajax)

| Ação | Método | Parâmetros |
|------|--------|------------|
| criar | POST | titulo, descricao |
| listar | GET | - |
| marcarConcluida | POST | id |
| excluir | POST | id |
| visualizarTabela | GET | - |

## 🎨 Customização

### Cores e Estilo
Edite o CSS no <style> do index.php para personalizar:

.stats-card {
    background: linear-gradient(45deg, #007bff, #0056b3);
    /* Altere as cores do gradiente */
}

### Adicionar Campos
Para adicionar novos campos (ex: prioridade):

1. Alterar a tabela no database.sql
2. Atualizar a classe Tarefa.php
3. Modificar o formulário no index.php
4. Ajustar os métodos em ajax_actions.php

## 🐛 Solução de Problemas

### Erro de Conexão com o Banco
- Verifique as credenciais no config.php
- Confirme se o banco lista_tarefas existe
- Certifique-se que a tabela tarefas foi criada

### Ajax Não Funciona
- Verifique se o jQuery está carregando
- Confirme o caminho dos arquivos PHP
- Verifique permissões de arquivo

### Interface Não Carrega
- Certifique-se que Bootstrap e Font Awesome estão acessíveis
- Verifique se há erros no console do navegador

## 📝 Licença

Este projeto está sob a licença MIT.

## 🤝 Contribuições

Contribuições são bem-vindas! Sinta-se à vontade para:
- Reportar bugs
- Sugerir novas funcionalidades
- Enviar pull requests

## 📞 Suporte

Se você encontrar algum problema ou tiver dúvidas:
1. Verifique esta documentação
2. Confirme os pré-requisitos do sistema
3. Abra uma issue no repositório

---

## ⚠️ OBSERVAÇÃO IMPORTANTE

Para garantir que todas as alterações realizadas na lista de tarefas sejam 
corretamente aplicadas e visualizadas, é necessário ATUALIZAR A PÁGINA no 
navegador após realizar operações de adição, exclusão ou marcação de tarefas.

Como atualizar:
- Pressione F5 no teclado
- Ou clique no ícone de atualizar do navegador
- Ou use Ctrl + R (Windows/Linux) ou Cmd + R (Mac)

Isso garante que a interface sincronize completamente com o banco de dados 
e todas as alterações sejam refletidas corretamente na visualização.

---

Desenvolvido com ❤️ usando PHP, MySQL e Bootstrap

Última atualização: Dezembro 2024