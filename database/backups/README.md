# database/backups/

> Arquivo de documentação da pasta — parte da arquitetura oficial do sistema Pitter Pan.
> Nenhum arquivo de funcionalidade foi criado aqui nesta etapa.

**Objetivo:**
Local padronizado para armazenar backups do banco gerados manualmente ou por rotina.

**Arquivos permitidos:**
Arquivos de backup (.sql, .sql.gz), nomeados com data.

**Arquivos proibidos:**
Qualquer coisa que não seja um backup.

**Dependências:**
Nenhuma.

**Quando usar:**
Antes de qualquer migration destrutiva, e em rotina periódica de segurança. **Esta pasta nunca deve ser versionada em controle de versão (git) — adicionar ao .gitignore.**
