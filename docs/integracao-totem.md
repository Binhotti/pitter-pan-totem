# Integração do projeto "pitter-pan-totem" à arquitetura

> Registra exatamente o que foi migrado do projeto enviado
> (`pitter-pan-totem-main.zip`) e para onde cada parte foi.
> Complementa `docs/arquitetura-sistema.md`.

## O que o projeto original fazia

Uma aplicação PHP simples, sem camadas, com 3 pontos de entrada
acessados diretamente por URL:

- `index.php` / `index.html` — tela do totem (fluxo de personalização
  de balão em 4 etapas), consumindo dados estáticos de `js/data.js` e
  enviando o pedido finalizado via `POST` para `api/orders.php`.
- `api/orders.php` — arquivo único com todo o CRUD de pedidos (tabela
  `orders`), usando `config/helpers.php` para respostas JSON e
  validação de campos.
- `admin/index.php` — painel de gestão de pedidos, consumindo o mesmo
  `api/orders.php` via `fetch`.

## Mapeamento — o que foi para onde

| Original | Migrado para | O que mudou |
|---|---|---|
| `index.php` (tela do totem) | `resources/views/pages/totem/index.php` | Só os caminhos de assets (`css/js/logo`), agora absolutos apontando para `public/`. Estrutura HTML idêntica. |
| `css/variables.css`, `reset.css`, `style.css`, `responsive.css` | `public/css/modules/totem/` | Copiados sem alteração. Mantidos isolados do Design System institucional (tokens/BEM diferentes, app full-screen). |
| `js/data.js`, `js/balloon.js` | `public/js/modules/totem/` | Sem alteração de lógica. |
| `js/app.js` | `public/js/modules/totem/app.js` | Apenas o endpoint da API: `fetch("api/orders.php")` → `fetch("/api/pedidos")`. |
| `admin/index.php` | `resources/views/pages/admin/pedidos.php` | Caminhos de assets e link "Abrir totem" atualizados. |
| `admin/style.css` | `public/css/modules/admin/style.css` | Sem alteração. |
| `admin/app.js` | `public/js/modules/admin/app.js` | `API_URL` atualizado para `/api/pedidos`. |
| `assets/logo-pitter-pan.png` | `public/assets/images/logos/` | Sem alteração. |
| `config/helpers.php` (`jsonResponse`, `readJsonBody`) | `app/Helpers/Http.php` | Mesmas funções, agora métodos estáticos de uma classe. |
| `config/helpers.php` (`textValue`, `allowedStatus`) | `app/Validators/PedidoValidator.php` | Mesma lógica, agrupada por entidade (Pedido) em vez de genérica. |
| `config/database.php` (`getPDO()`, fora do zip por ser gitignored) | `app/Config/database.php` + `app/Bootstrap/Database.php` | Configuração e conexão separadas; mesma responsabilidade. |
| `api/orders.php` (todo o arquivo) | `app/Controllers/Api/PedidoApiController.php` + `app/Repositories/PedidoRepository.php` | Mesmo comportamento e mensagens, dividido em Controller (orquestração) e Repository (SQL). Nenhuma query foi alterada, só reorganizada por método. |
| Tabela `orders` | `database/schema/pedidos.sql` | Renomeada para seguir a convenção do sistema (tabela em português). Colunas mantidas em inglês, sem alteração, para não exigir mudança nas queries migradas. |

## O que ficou de fora (decisão consciente)

- **Autenticação do painel admin:** o projeto original não tinha login,
  e a rota `/admin/pedidos` continua aberta por enquanto — sinalizado
  como pendência em `app/Controllers/Http/Admin/PedidoAdminController.php`.
- **Migração do catálogo estático (`js/data.js`) para banco:** ocasiões,
  modelos, fontes e cores continuam vindo do JS estático, como no
  projeto original. O módulo `app/Models/Balao.php` já existente na
  arquitetura permanece como scaffold para essa migração futura, se
  decidida.

## O que passou a existir e não existia antes

- `app/Bootstrap/Router.php` e `app/Bootstrap/app.php` — um roteador
  mínimo, necessário porque a arquitetura exige um único ponto de
  entrada (`public/index.php`); o projeto original acessava cada
  arquivo PHP diretamente por URL.
- `routes/web.php` (`/totem`, `/admin/pedidos`) e `routes/api.php`
  (`/api/pedidos`) — mapa explícito das rotas, no lugar do acesso
  direto a `index.php`, `admin/index.php` e `api/orders.php`.
- `public/.htaccess` — agora com regras reais (não apenas comentadas),
  necessárias para rotear `/totem`, `/admin/pedidos` e `/api/pedidos`
  para `public/index.php`, mantendo arquivos estáticos (CSS/JS/HTML)
  servidos diretamente.
