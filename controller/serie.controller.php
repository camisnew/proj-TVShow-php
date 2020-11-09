<?php
    session_start();
    require '../util/util.class.php';

    include '../model/serie.class.php';
    include '../dao/seriedao.class.php';

    switch($_GET['op']) {
        case 'cadastrar': 
            $name = $_POST['name'];
            $releaseyear = $_POST['releaseYear'];
            $episodes = $_POST['episodes'];
            $seasons = $_POST['seasons'];
            if(empty($name) || empty($releaseyear) || empty($episodes) || empty($seasons)) {
                return 'Preencha os campos.';
            } else if(! Util::testRegex('/^[A-Za-zÀ-Úà-ú ]{2,40}$/',$name)) {
                return 'Nome fora do padrão';
            } else if(! Util::testYear($releaseyear)) {
                return 'Não é ano válido';
            } else if(! Util::testRegex('/^[0-9]{8,20}$/',$episodes)) {
                return 'Não é um número de episódios';
            } else if(! Util::testRegex('/^[0-9]{8,20}$/',$seasons)) {
                return 'Não é ';
            } else {
                $serie = new Serie();
                $serie->setName($name);
                $serie->setRealeaseyear($realeaseyear);
                $serie->setEpisodes($episodes);
                $serie->setSeasons($seasons);
                //Aqui enviamos para o BANCO:
                $serieDAO = new SerieDAO();
                $SerieDAO->createSeries($serie);
                header('location:../view/confirmacadastro.html');
            }
        break;

        case 'deletar':
            $serieDAO = new SerieDAO();
            $serieDAO->deleteSeries($_REQUEST['idserie']);
            header('location:../view/buscarserie.php');
        break;

        case 'alterar':
            $idserie = $_REQUEST['idserie'];
            $query = 'WHERE idserie = '.$idserie;
            //criamos um objeto para acessar as funções do DAO:
            $serieDAO = new SerieDAO();
            //criamos uma variável para pegar o resultado da busca:
            $series = array();
            //Atribuimos o resultado na busca na variável:
            $series = $SerieDAO->search($query);
            //como iremos passar o resultado da busca com segurança:
            //SESSION com a função SERIALIZE - onde guarda uma string com respostas:
            $_SESSION['series']=serialize($series);
            //direciono para a página que terá o alterar:
            header("location:../view/alterarserie.php");
        break;

        case 'confirmaralteracao':
            $serie = new Serie();
            $serie->idSerie = $_POST['idserie'];
            $serie->name = $_POST['name'];
            $serie->releaseYear = $_POST['releaseYear'];
            $serie->email = $_POST['txtemail'];
            $serie->mensagem = $_POST['txtmensagem'];
            $SerieDAO = new SerieDAO();
            $SerieDAO->updateSerie($serie);
            header('location:../view/buscarcontatos.php');
        break;
        default:
            echo "Errou o nome do case!!!";
        }
?>