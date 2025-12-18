<?php
header('Content-Type: application/json');

$pedidos = [];
if (is_dir('pedidos')) {
    $arquivos = glob('pedidos/*.txt');
    foreach($arquivos as $file) {
        $linhas = file($file);
        preg_match('/PEDIDO #(\d+)/', $linhas[0], $idMatch);
        preg_match('/Cliente: (.*?)\n/', $linhas[1], $clienteMatch);
        preg_match('/TOTAL GERAL: R\$(.*?)\n/', end($linhas), $totalMatch);
        
        $pedidos[] = [
            'id' => $idMatch[1] ?? '0',
            'cliente' => trim($clienteMatch[1] ?? 'N/A'),
            'total' => $totalMatch[1] ?? '0',
            'arquivo' => basename($file),
            'download_url' => $file
        ];
    }
}

echo json_encode(array_slice(array_reverse($pedidos), 0, 5)); // Últimos 5
?>
