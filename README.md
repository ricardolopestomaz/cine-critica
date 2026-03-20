# 🎬 Cine-Crítica

Sistema web para gerenciamento, consulta e avaliação de acervo cinematográfico. Este projeto foi desenvolvido como requisito prático para a disciplina de **Engenharia de Software** na Universidade Federal do Tocantins (UFT).

O sistema foca em uma arquitetura modular, separando a lógica de negócio da apresentação, garantindo um código limpo e de fácil manutenção.

## 📂 Estrutura do Projeto

A organização de pastas segue padrões de mercado para garantir escalabilidade e organização via **GitFlow**:

### 1. `/public`
Ponto de entrada da aplicação. É a pasta que deve ser servida pelo Apache.
- `index.php`: Dashboard principal com a listagem dinâmica de filmes.
- `css/`: Estilização modular (variáveis, layout base e temas).
- `js/`: Scripts para interações no front-end e validações.
- `uploads/`: Armazenamento local das capas dos filmes cadastrados.

### 2. `/includes`
Componentes reutilizáveis para evitar repetição de código (DRY - Don't Repeat Yourself).
- `header.php`: Barra de navegação, busca e menu global.
- `footer.php`: Rodapé e scripts globais.

### 3. `/modules`
Módulos que segmentam as funcionalidades principais do sistema:
- `cadastro/`: Lógica de inserção de novos títulos e processamento de imagens.
- `busca/`: Algoritmos de filtragem por nome, ano e categorias.
- `avaliacoes/`: Sistema de notas e processamento de comentários dos usuários.

### 4. `/config`
Configurações críticas e sensíveis do ambiente.
- `db_connect.php`: Gerenciamento da conexão PDO/MySQL.

### 5. `/docs`
Documentação técnica do projeto.
- `database.sql`: Script de criação das tabelas e relacionamentos.

---

## 🚀 Como rodar o projeto localmente

1. **Clone o repositório** dentro da pasta `htdocs` do seu servidor local (XAMPP/WAMP).
2. **Configure o Banco de Dados**:
   - Ative o MySQL no seu painel de controle.
   - Importe o arquivo `/docs/database.sql` via PHPMyAdmin.
3. **Ajuste a Conexão**: Verifique as credenciais no arquivo `/config/db_connect.php`.
4. **Acesse no navegador**: `http://localhost/cine-critica-uft/public/`

## 🛠️ Tecnologias Utilizadas
- **Linguagem:** PHP 8.x
- **Banco de Dados:** MySQL
- **Interface:** HTML5 / CSS3 (Grid Layout & Flexbox)
- **Versionamento:** Git com fluxo GitFlow

## 📄 Licença
Este projeto está sob a licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

---
Desenvolvido por **Ricardo Lopes Tomaz** - Acadêmico de Computação/Sistemas (UFT).
