# database/migrations/

> Arquivo de documentação da pasta — parte da arquitetura oficial do sistema Pitter Pan.
> Nenhum arquivo de funcionalidade foi criado aqui nesta etapa.

**Objetivo:**
Registrar o histórico versionado de toda alteração estrutural feita no banco (criação/alteração de tabelas), permitindo reconstruir o banco do zero a qualquer momento, na ordem correta.

**Arquivos permitidos:**
Um arquivo por alteração, nomeado com data/sequência + descrição (ex.: 2026_08_01_criar_tabela_baloes.sql).

**Arquivos proibidos:**
Dados de exemplo (isso é de seeds/), backups completos (isso é de backups/).

**Dependências:**
database/schema/ como referência do estado final desejado.

**Quando usar:**
Toda vez que uma tabela nova for criada ou uma coluna existente for alterada — nunca editar uma tabela em produção sem uma migration correspondente registrada aqui.
