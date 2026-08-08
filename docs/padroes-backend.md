# Padrões de Backend — Sistema Pitter Pan

> Complementa `docs/conventions.md` (que documenta apenas o front-end).
> Nenhum destes dois arquivos substitui o outro.

## Classes PHP
- `PascalCase`, singular: `BalaoController`, `PedidoService`.
- Um arquivo por classe, nome do arquivo idêntico ao nome da classe.

## Métodos PHP
- `camelCase`, verbo + substantivo: `buscarPorId()`, `criarPedido()`.

## Arquivos de configuração
- `snake_case`, um assunto por arquivo: `database.php`, `mail.php`.

## Views PHP
- `kebab-case`, mesma convenção já usada em `public/css/` e `public/js/`:
  `personalizar-balao.php`.

## Banco de dados
- Tabelas em `snake_case`, plural: `pedidos`, `baloes`.
- Chave primária sempre `id`.
- Chave estrangeira sempre `nome_tabela_id` (ex.: `cliente_id`).

## Camadas — regra de dependência
Controller -> Service -> Repository -> Model -> Banco.
Nunca o caminho inverso. Nenhuma camada pula outra (Controller nunca
chama Repository diretamente, por exemplo).

## Módulos novos
Todo módulo de negócio novo (Clientes, Estoque, Financeiro etc.) replica
exatamente a estrutura do módulo de balões: um Controller, um Model, um
Repository, um Service, uma pasta de views — nunca um arquivo fora do
padrão.

Ver `docs/arquitetura-sistema.md` para a árvore completa e o raciocínio
por trás de cada decisão.
