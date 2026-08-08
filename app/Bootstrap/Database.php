<?php

declare(strict_types=1);

/**
 * Arquivo: Database.php
 * Camada: Bootstrap
 *
 * Responsabilidade:
 *   Abrir e reutilizar (singleton) a conexão PDO com o MySQL, a partir
 *   dos parâmetros de app/Config/database.php.
 *
 * Substitui, de forma orientada a objetos, a função global getPDO()
 *   usada no projeto original "pitter-pan-totem" (config/database.php),
 *   evitando poluir o namespace global e concentrando a criação da
 *   conexão em um único ponto auditável.
 *
 * Usado por:
 *   app/Repositories/*.php (única camada autorizada a consumir esta
 *   classe diretamente).
 */

namespace App\Bootstrap;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $conexao = null;

    public static function conexao(): PDO
    {
        if (self::$conexao === null) {
            $config = require __DIR__ . '/../Config/database.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['porta'],
                $config['banco'],
                $config['charset']
            );

            try {
                self::$conexao = new PDO($dsn, $config['usuario'], $config['senha'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $exception) {
                throw new PDOException('Não foi possível conectar ao banco de dados: ' . $exception->getMessage());
            }
        }

        return self::$conexao;
    }
}
