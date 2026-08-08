<?php

require __DIR__ . '/../../app/Bootstrap/Router.php';

class TestController
{
    public function index(): void
    {
        echo 'ok';
    }
}

$router = new App\Bootstrap\Router();
$router->get('/closure', function (): void {
    echo 'closure-ok';
});

ob_start();
$router->despachar('GET', '/closure');
$result = ob_get_clean();

if ($result !== 'closure-ok') {
    fwrite(STDERR, "Teste falhou: esperado 'closure-ok', recebeu '$result'\n");
    exit(1);
}

echo "Router test passed\n";
