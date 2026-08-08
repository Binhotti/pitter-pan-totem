# Segurança — Sistema Pitter Pan

> Complementa `docs/arquitetura-sistema.md`.

## Fronteira de entrada
Todo tráfego passa exclusivamente por `public/index.php`. Nenhum outro
arquivo PHP do projeto é acessível diretamente via URL — reforçado por
`.htaccess` em `public/`.

## Dados sensíveis
- Credenciais de banco, chaves de API e afins vivem apenas em `.env`
  (nunca versionado — ver `.gitignore`).
- `app/Config/*.php` lê de variáveis de ambiente, nunca contém valores
  reais hardcoded.

## Validação e sanitização
- Toda entrada de usuário passa por `app/Validators/` antes de qualquer
  processamento.
- Toda saída de dado para tela passa por `app/Security/Sanitizer.php`,
  prevenindo XSS.
- Toda query SQL é preparada (nunca concatenada com entrada do
  usuário), e vive exclusivamente em `app/Repositories/`.

## Autenticação e autorização
- Concentradas em `app/Security/` e aplicadas via
  `app/Middlewares/AuthMiddleware.php`.

## Uploads
- `public/uploads/` — apenas arquivos que o próprio sistema gera para
  exibição pública (ex.: foto de balão finalizado).
- `storage/private-uploads/` — qualquer upload sensível, nunca acessível
  por URL direta.

## Backups e logs
- `database/backups/` e `storage/logs/` nunca são versionados em
  controle de versão (git) — ver `.gitignore`.

## Erros
- Tratados centralmente por `app/Exceptions/`, evitando que mensagens
  técnicas ou dados sensíveis "vazem" para o usuário final.
