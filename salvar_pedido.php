<?php
header('Content-Type: application/json');

if ($_POST['acao'] == 'salvar_pedido') {
    $id = str_pad($_POST['id'], 3, '0', STR_PAD_LEFT);
    $cliente = preg_replace('/[^a-zA-Z0-9\s]/', '_', strtolower($_POST['cliente']));
    $cliente = preg_replace('/\s+/', '_', $cliente);
    
    // ✅ CAMINHO ABSOLUTO QUE VOCÊ QUER
    $caminhoCompleto = 'D:/SDCard/Theras/pedidos/';
    
    // Cria pasta se não existir
    if (!is_dir($caminhoCompleto)) {
        mkdir($caminhoCompleto, 0777, true);
    }
    
    $filename = $caminhoCompleto . "{$id}_{$cliente}.txt";
    
    // Conteúdo do pedido
    $conteudo = "=== PEDIDO #{$id} ===\n";
    $conteudo .= "Cliente: {$_POST['cliente']}\n";
    $conteudo .= "WhatsApp: {$_POST['telefone']}\n";
    $conteudo .= "Data: " . date('d/m/Y H:i:s') . "\n";
    $conteudo .= "Forma Pagto: {$_POST['pagto']}\n\n";
    $conteudo .= "ITENS:\n" . str_repeat("=", 50) . "\n\n";
    
    $itens = json_decode($_POST['itens'], true);
    foreach($itens as $index => $item) {
        $conteudo .= ($index + 1) . ". " . $item['desc'] . "\n";
        $conteudo .= "   Qtd: " . $item['qtde'] . " | R$ " . number_format($item['valor'], 2, ',', '.') . "\n";
        $conteudo .= "   Total Item: R$ " . number_format($item['total_item'], 2, ',', '.') . "\n";
        $conteudo .= "   Escala: " . $item['escala'] . " | Categoria: " . $item['categoria'] . "\n";
        if (!empty($item['obs'])) $conteudo .= "   Obs: " . $item['obs'] . "\n";
        $conteudo .= "\n";
    }
    
    $total = $_POST['total'];
    $conteudo .= str_repeat("=", 50) . "\n";
    $conteudo .= "TOTAL GERAL: R$ " . number_format($total, 2, ',', '.') . "\n";
    $conteudo .= "================ FIM PEDIDO #{$id} ================";
    
    // ✅ SALVA EXATAMENTE ONDE VOCÊ QUER
    $resultado = file_put_contents($filename, $conteudo);
    
    if ($resultado !== false) {
        echo json_encode([
            'success' => true,
            'arquivo' => $filename,
            'caminho' => $filename,
            'download_url' => str_replace('D:/SDCard/Theras/pedidos/', 'download/', $filename) // Para download via web
        ]);
    } else {
        echo json_encode(['success' => false, 'erro' => 'Falha ao salvar arquivo']);
    }
}
?>