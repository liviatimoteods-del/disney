<?php

$paginaAtual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($paginaAtual < 1) $paginaAtual = 1;

$url = "https://api.disneyapi.dev/character?pageSize=50&page=" . $paginaAtual;

$context = stream_context_create(['http' => ['timeout' => 10]]);
$response = @file_get_contents($url, false, $context);
$data = $response ? json_decode($response, true) : null;

$personagens = $data['data'] ?? [];
$totalPaginas = $data['info']['totalPages'] ?? 190; 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Disney Characters</title>
    <link href="style.css?v=<?php echo rand();?>" type="text/css" rel="stylesheet">
</head>
<body>

    <div class="header">
        <img src="logodisney.png" alt="disney" class="disney"/>
        <h1>VENHA EXPLORAR ESSE UNIVERSO MÁGICO!</h1>
    </div>

    <div class="Pesquisa">
        <input type="text" id="inputPesquisa" placeholder="Pesquise pelo personagem...">
    </div>

    <div class="personagens">
        <?php foreach ($personagens as $personagem): ?>
            <div class="card">
                <h2><?php echo $personagem['name']; ?></h2>
                
                <h3>
                    <?php 
                  
                    $filmes = !empty($personagem['films']) ? implode(", ", array_slice($personagem['films'], 0, 3)) : "Nenhum filme";
                    echo "Filmes: " . $filmes; 
                    ?>
                </h3>
              
                    
                    <img
                        src="<?php echo $personagem['imageUrl'] ?? 'https://via.placeholder.com/150'; ?>"
                        alt="<?php echo $personagem['name']; ?>"
                    >
                </div>
            
        <?php endforeach; ?>
    </div>

    <ul class="pagination">
        <?php if($paginaAtual > 1): ?>
            <li><a href="?page=<?php echo $paginaAtual - 1; ?>">&laquo;</a></li>
        <?php endif; ?>

        <?php 
        $inicio = max(1, $paginaAtual - 2);
        $fim = min($totalPaginas, $paginaAtual + 2);
        for ($i = $inicio; $i <= $fim; $i++): 
        ?>
            <li class="<?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if($paginaAtual < $totalPaginas): ?>
            <li><a href="?page=<?php echo $paginaAtual + 1; ?>">&raquo;</a></li>
        <?php endif; ?>
    </ul>

    <div class="chao">
         <img src="castelo.png" alt="chao" class="chao"/>
    </div>
    
    <div class="footer"> 
        <p>@ Direitos Reservados</p>
    </div> 

</body>
</html>

            <script>
    const inputBusca = document.getElementById('inputPesquisa');
    const cards = document.querySelectorAll('.card');

    inputBusca.addEventListener('input', () => {
        const textoDigitado = inputBusca.value.toLowerCase();

        cards.forEach(card => {
           
            const nomePersonagem = card.querySelector('h2').textContent.toLowerCase();
            
      
            if (nomePersonagem.includes(textoDigitado)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
    </script>

   