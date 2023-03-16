<?php

declare(strict_types=1);
require_once 'NeuroneLia.php';
/**
 * @author fogas fogasy 
 * @version 1.4
 * @access public
 */

class Lia
{

  public ?string $repo = null;
  public string|null|array $ques = null;
  public string $monNom = 'lia';
  /**
   * exp
   *
   * @var mixed
   */
  public $exp;

  public function __construct()
  {

    if (session_status() == PHP_SESSION_NONE) {
      session_start();
      //session_id("lia");
    }
  }

  /**
   * jsDec
   *
   * @param  mixed $str
   * @return string
   */
  public function jsDec(string $str): string
  {
    $res = $this->Dec($str);
    return json_encode($res, JSON_THROW_ON_ERROR);
  }

  /**
   * Dec
   *
   * @param  mixed $str
   * @return array
   */
  public function Dec(string $str): array
  {
    $tex = [];
    $neuron = new NeuroneLia();
    $str = $this->noAccent($str);
    $str = preg_replace('/[,;:?!]/i', '', $str);

    $this->ques = $str;
    $neuron->QuestionPosee($str);
    $this->repo = $this->getMemoirUser();

    $keywords = [
      'bonjour' => ['Salutation', 0],
      'salut' => ['Salutation', 0],
      'salutation' => ['Salutation', 0],
      'bonsoir' => ['Salutation', 0],
      'chalut' => ['Salutation', 0],
      'bonne nuit' => ['Salutation', 0],
      'je m\'appelle' => ['Appelle', 1],
      'je me nomme' => ['Appelle', 1],
      'ou est' => ['OuEst', 1],
      'aime tu' => ['AimeTu', 1],
      'tu aime' => ['AimeTu', 1],
      'tu est' => ['Tues', 0],
      'tu es' => ['Tues', 0],
      'qui est' => ['QuiEst', 1],
      'qui sont' => ['QuiEst', 1],
      'calcul' => ['Calcul', 0],
      'comment va tu' => ['vaTu', 0],
      'ca va bien' => ['vaTu', 0],
      'comment va' => ['vaTu', 0],
      'ca va' => ['vaTu', 0],
      'la reponse a la question' => ['Reponse', 1],
      'la reponse numero' => ['Reponse', 1],
      'la question numero' => ['Question', 1],
      'la grande question' => ['Questions', 1],
      'la question' => ['Questions', 1],
      'la grande reponse' => ['Reponses', 1],
      'la reponse' => ['Reponses', 1],
      'quel heure est il' => ['Heure', 0],
      'il est quel heure' => ['Heure', 0],
      'y est quel heure' => ['Heure', 0],
      'c\'est quelle heure' => ['Heure', 0],
      'quel heure il est' => ['Heure', 0],
      'coucou' => ['Coucou', 0], 'merci a toi' => ['Merci', 0],

    ];
    
    uksort($keywords, function ($a, $b) {
      return strlen($b) - strlen($a);
    });


    foreach ($keywords as $keyword => $function) {

      if (str_contains($this->ques, $keyword)) {

        $str = trim(preg_replace('#' . $keyword . '#', '', $this->ques));
        $m = $function[1];
        $str2 = str_word_count($keyword, 1)[0] ?? null;
        $this->repo = call_user_func_array([$neuron, $function[0]], [$str, $str2]);
        if (!empty($neuron->exp)) {
          $this->exp = $neuron->exp;
        }

        break;
      }
    }

    if (!empty($this->txt) && empty($this->exp)) {

      $this->exp = $this->Expretion('parle');
    }

    if (!empty($this->txt)) {
      $this->txt = $this->MemoirUser();
    }

    if (!empty($_SESSION['question'])) {
      $ques = ($_SESSION['question']);
    } else {
      $ques = '';
    }

    return ['txt' => $this->repo, 'img' => $this->exp, 'ques' => $ques];
  }

  /**
   * getMemoirUser
   * lie la mémoire coter utilisateur
   * @return string
   */
  private function getMemoirUser(): ?string
  {
    $r = [];
    //memoire si une question a ete déja posée
    if (!empty($_SESSION['reponse']['fois'][$this->ques])) {
      $_SESSION['reponse']['fois'][$this->ques]++;
      if ($_SESSION['reponse']['fois'][$this->ques] >= 5) {
        $r[] = "re-";
        $r[] = "déja dit, ";
        $r[] = "mmm... ";
        $r[] = "je te l'ai déja dit ";
        $rc = count($r);
        $r1 = random_int(0, $rc - 1);

        return  $r[$r1] . $_SESSION['reponse'][$this->ques];
      } else {

        return $_SESSION['reponse'][$this->ques];
      }
    }
    return null;
  }



  /**
   * Expretion
   *
   * @param  mixed $exp
   * @return string
   */
  public function Expretion(string $exp = null): string
  {

    switch ($exp) {
      case 'ennuie':
        $img = 'ennuie.png';
        break;
      case 'parle':
        $img = 'parle.png';
        break;
      case 'etoile':
        $img = 'etoile.png';
        break;
      case 'horor':
        $img = 'horor.png';
        break;
      case 'pleur':
        $img = 'pleur.png';
        break;
      case 'colere':
        $img = 'colere.png';
        break;
      case 'dort':
        $img = 'dort.png';
        break;
      case 'enerve':
        $img = 'enerve.png';
        break;
      case 'heureuse':
        $img = 'heureuse.png';
        break;
      case 'ho':
        $img = 'ho.png';
        break;
      case 'interrogation':
        $img = 'interrogation.png';
        break;
      case 'joyeuse':
        $img = 'joyeuse.png';
        break;
      case 'morte':
        $img = 'morte.png';
        break;
      case 'navre':
        $img = 'navre.png';
        break;
      case 'peur':
        $img = 'peur.png';
        break;
      case 'repos':
        $img = 'repos.png';
        break;
      case 'rire':
        $img = 'rire.png';
        break;
      case 'surpris':
        $img = 'surpris.png';
        break;
      case 'triste':
        $img = 'triste.png';
        break;
      default:
        $img = 'neutre.png';
        break;
    }

    return $this->exp = $img;
  }

  /**
   * noAccent
   *
   * @param  mixed $str
   * @return string
   */
  private function noAccent(string $str): string
  {
    $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
    $str = trim($str);
    $str = (strtolower($str));
    $str = preg_replace('#\'#', ' ', $str);

    $str = preg_replace('#  #', ' ', $str);
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
    $str = preg_replace('#&[^;]+;#', '', $str);
    $str = preg_replace('#ç#', 'c', $str);
    $str = preg_replace('#è|é|ê|ë#', 'e', $str);
    $str = preg_replace('#à|á|â|ã|ä|å#', 'a', $str);
    $str = preg_replace('#ì|í|î|ï#', 'i', $str);
    $str = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $str);
    $str = preg_replace('#ù|ú|û|ü#', 'u', $str);
    $str = preg_replace('#ý|ÿ#', 'y', $str);
    $str = preg_replace('#&ccedil;#', 'c', $str);
    $str = preg_replace('#&egrave;|&eacute;|&ecirc;|&euml;#', 'e', $str);
    $str = preg_replace('#&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;#', 'a', $str);
    $str = preg_replace('#&igrave;|&iacute;|&icirc;|&iuml;#', 'i', $str);
    $str = preg_replace('#&otilde;|&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;#', 'o', $str);
    $str = preg_replace('#&ugrave;|&uacute;|&ucirc;|&uuml;#', 'u', $str);
    $str = preg_replace('#&yacute;|&yuml;#', 'y', $str);

    return $str;
  }
}
