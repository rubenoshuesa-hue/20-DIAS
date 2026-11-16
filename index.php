<?php
// =====================================================
//  MI PRIMERA SEMANA CON PHP  ·  index.php
//  Ejecutar:  php -S localhost:8000   (en esta carpeta)
//  Abrir:     http://localhost:8000
// =====================================================
session_start();

/* ---------- 1. DATOS (edita aquí tu contenido) ---------- */
$autor = "Rubén";          // <- cámbialo
$curso = "4 ESO economia";   // <- cámbialo

// Versiones de la web: cada una desbloquea cosas nuevas
$versiones = [
    1 => ["Texto estático",       "HTML metido en un .php. Todavía no hay PHP de verdad."],
    2 => ["Variables y arrays",   "Los datos viven en arrays y la página los muestra."],
    3 => ["Bucles y funciones",   "foreach para repetir y funciones para no copiar código."],
    4 => ["Formularios y sesión", "La web recibe datos del visitante con $_POST."],
    5 => ["Base de datos",        "Siguiente reto: guardar todo en MySQL con PDO."],
];
$v = isset($_GET['v']) ? (int)$_GET['v'] : 4;
if (!isset($versiones[$v])) $v = 4;

// Diario de la semana (EDITA con lo que de verdad hiciste cada día)
$dias = [
    ["Día 1", "Instalar y primer echo",     "Instalé PHP, ejecuté el servidor integrado y escribí mi primer echo.",        'echo "Hola, mundo";'],
    ["Día 2", "Variables y tipos",          "Strings, enteros, booleanos y la concatenación con punto.",                    '$nombre = "Ana";' . "\n" . 'echo "Hola, " . $nombre;'],
    ["Día 3", "Arrays y bucles",            "Arrays asociativos y foreach para recorrerlos.",                               'foreach ($dias as $d) {' . "\n" . '    echo $d["titulo"];' . "\n" . '}'],
    ["Día 4", "Funciones",                  "Dejé de repetir código: parámetros, return y valores por defecto.",            'function saludar($n = "mundo") {' . "\n" . '    return "Hola, $n";' . "\n" . '}'],
    ["Día 5", "Formularios",                "$_POST y htmlspecialchars para no dejar la web abierta a ataques XSS.",        '$txt = htmlspecialchars($_POST["txt"]);'],
    ["Día 6", "Sesiones",                   "$_SESSION para recordar cosas entre una petición y otra.",                     'session_start();' . "\n" . '$_SESSION["visitas"]++;'],
    ["Día 7", "Juntarlo todo",              "Esta página: datos en arrays, bucles, funciones, formulario y sesión.",        '// Estás viéndola :)'],
];

/* ---------- 2. FUNCIONES ---------- */
function e(string $t): string {            // atajo para escapar HTML
    return htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
}

/* ---------- 3. LÓGICA (formulario + sesión) ---------- */
$_SESSION['visitas'] = ($_SESSION['visitas'] ?? 0) + 1;
$_SESSION['preguntas'] = $_SESSION['preguntas'] ?? [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $v >= 4) {
    $nombre   = trim($_POST['nombre'] ?? '');
    $pregunta = trim($_POST['pregunta'] ?? '');
    if ($nombre === '' || $pregunta === '') {
        $error = 'Rellena el nombre y la pregunta.';
    } else {
        $_SESSION['preguntas'][] = [
            'nombre'   => mb_substr($nombre, 0, 40),
            'pregunta' => mb_substr($pregunta, 0, 200),
            'hora'     => date('H:i'),
        ];
        header("Location: ?v=$v#preguntas");   // Post/Redirect/Get
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mi primera semana con PHP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,800&family=DM+Sans:wght@400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#13112a; --glow:#3b2f8f; --card:#1e1b3f; --card2:#282454; --line:#37336a;
  --ink:#ece9fb; --mute:#a9a5d1; --php:#8f94e0; --amber:#ffc857; --mint:#5ee0b0;
  --head:"Bricolage Grotesque",system-ui,sans-serif;
  --body:"DM Sans",system-ui,sans-serif;
  --code:"JetBrains Mono",ui-monospace,Consolas,monospace;
}
@media (prefers-color-scheme:light){
  :root{--bg:#f6f4ff;--glow:#d9d4ff;--card:#fff;--card2:#ebe8fb;--line:#d5d1f0;--ink:#1d1b3a;--mute:#5f5b8c;--php:#5a5fb8;--amber:#b87800;--mint:#1c8a63}
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;color:var(--ink);font:1.05rem/1.7 var(--body);
  background:radial-gradient(900px 500px at 15% -5%,var(--glow),transparent 70%),var(--bg)}
main{max-width:980px;margin:0 auto;padding:3rem 1.25rem 5rem}
h1,h2,h3{font-family:var(--head);margin:0;line-height:1.05}
h2{font-size:2rem;margin:5rem 0 .6rem;letter-spacing:-.01em}
.sub{color:var(--mute);margin:0 0 1.8rem;max-width:40em}
code{font-family:var(--code);font-size:.9em;color:var(--amber)}
:focus-visible{outline:3px solid var(--amber);outline-offset:3px}

/* HERO */
.hero{padding:2rem 0 1rem}
.tag{font:500 clamp(1.1rem,3vw,1.5rem) var(--code);color:var(--php)}
.hero h1{font-size:clamp(2.8rem,9.5vw,6rem);font-weight:800;letter-spacing:-.035em;margin:.3rem 0 .5rem}
.hero .intro{color:var(--mute);max-width:36em;font-size:1.15rem;margin:0 0 2rem}
.hero .intro b{color:var(--ink);font-weight:500}

/* ventana de editor */
.win{background:var(--card);border:1px solid var(--line);border-radius:14px;overflow:hidden;box-shadow:0 24px 60px -30px #000a}
.bar{display:flex;align-items:center;gap:.4rem;padding:.65rem .9rem;background:var(--card2);border-bottom:1px solid var(--line);font:.8rem var(--code);color:var(--mute)}
.bar i{width:.7rem;height:.7rem;border-radius:50%;background:#ff6b6b}
.bar i:nth-child(2){background:var(--amber)} .bar i:nth-child(3){background:var(--mint)}
.bar span{margin-left:.6rem}
.win pre{margin:0;padding:1rem 1.2rem;overflow-x:auto;font:.9rem/1.65 var(--code);color:var(--ink)}
.live{padding:1.1rem 1.3rem;font:.98rem/1.9 var(--code)}
.live .k{color:var(--php)} .live .s{color:var(--mint)}
.live p{margin:.7rem 0 0;font:.92rem var(--body);color:var(--mute)}

/* STEPPER de versiones */
.steps{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(5,1fr);gap:.5rem}
.steps a{display:block;text-decoration:none;color:var(--mute);padding-top:1.1rem;position:relative;font:500 .95rem/1.3 var(--head)}
.steps a::before{content:"";position:absolute;top:0;left:0;right:0;height:5px;border-radius:5px;background:var(--line)}
.steps .done a{color:var(--ink)}
.steps .done a::before{background:var(--php)}
.steps [aria-current] {color:var(--ink)}
.steps [aria-current]::before{background:var(--amber)}
.steps a:hover::before{background:var(--amber)}
.steps small{display:block;font:400 .8rem var(--code);color:var(--mute)}
.vdesc{margin:1.4rem 0 0;padding:1rem 1.2rem;background:var(--card);border-left:5px solid var(--amber);border-radius:0 10px 10px 0}

/* DIARIO */
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,420px),1fr));gap:1.2rem;list-style:none;margin:0;padding:0}
.day{display:flex;flex-direction:column}
.day .txt{padding:1.1rem 1.2rem .9rem}
.day h3{font-size:1.35rem}
.chip{display:inline-block;font:500 .8rem var(--code);color:var(--bg);background:var(--php);border-radius:99px;padding:.1rem .7rem;margin-bottom:.6rem}
.day p{margin:.4rem 0 0;color:var(--mute)}
.day .win{border:0;border-top:1px solid var(--line);border-radius:0;box-shadow:none;margin-top:auto;background:#0e0c20;color:#ece9fb}
.day{border-radius:14px;overflow:hidden;background:var(--card);border:1px solid var(--line)}
.day pre{color:#ece9fb}

/* FORM */
.cols{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:1.5rem;align-items:start}
@media(max-width:760px){.cols{grid-template-columns:1fr}.steps{grid-template-columns:1fr 1fr}}
form{display:grid;gap:.9rem;padding:1.3rem;background:var(--card);border:1px solid var(--line);border-radius:14px}
label{font:500 .95rem var(--head)}
input,textarea{width:100%;margin-top:.3rem;padding:.65rem .8rem;font:inherit;color:var(--ink);background:var(--bg);border:1px solid var(--line);border-radius:8px}
input:focus,textarea:focus{border-color:var(--php);outline:none;box-shadow:0 0 0 3px #8f94e055}
button{justify-self:start;padding:.7rem 1.3rem;font:500 1rem var(--head);background:var(--amber);color:#1d1b3a;border:0;border-radius:8px;cursor:pointer;transition:transform .15s}
button:hover{transform:translateY(-2px)}
.err{margin:0;color:#ff8c8c;font-weight:500}
.preg{list-style:none;margin:0;padding:0;display:grid;gap:.7rem}
.preg li{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:.8rem 1rem}
.preg b{font-family:var(--head)} .preg small{color:var(--mute)}
.vacio{color:var(--mute)}
.lock{border:2px dashed var(--line);border-radius:14px;padding:1.2rem 1.4rem;color:var(--mute)}
footer{margin-top:5rem;color:var(--mute);font-size:.95rem;border-top:1px solid var(--line);padding-top:1.2rem}
@media (prefers-reduced-motion:reduce){html{scroll-behavior:auto}button{transition:none}}
</style>
</head>
<body>
<main>

<header class="hero">
  <div class="tag">&lt;?php</div>
  <h1>Mi primera semana con PHP</h1>
  <p class="intro"><b><?= e($autor) ?></b> · <?= e($curso) ?>. Esta web la hice a ratos, entre el examen de mates, con la documentación oficial y un poco de ayuda de Claude.</p>
<?php if ($v >= 2): ?>
  <div class="win" aria-label="Datos generados por PHP en el servidor">
    <div class="bar"><i></i><i></i><i></i><span>servidor.php</span></div>
    <div class="live">
      <span class="k">PHP_VERSION</span> = <span class="s"><?= e(PHP_VERSION) ?></span><br>
      <span class="k">date()</span> = <span class="s"><?= date('d/m/Y H:i:s') ?></span><br>
      <span class="k">$_SESSION['visitas']</span> = <span class="s"><?= (int)$_SESSION['visitas'] ?></span>
      <p>Nada de esto lo escribí yo: lo calcula PHP cada vez que cargas la página.</p>
    </div>
  </div>
<?php endif; ?>
</header>

<section aria-labelledby="evo">
  <h2 id="evo">Cómo evoluciona la web</h2>
  <p class="sub">Cada versión desbloquea algo nuevo. Pulsa de la 1 a la 5 y mira cómo cambia esta misma página.</p>
  <ol class="steps">
<?php foreach ($versiones as $n => [$titulo, $desc]): ?>
    <li<?= $n <= $v ? ' class="done"' : '' ?>><a href="?v=<?= $n ?>"<?= $n === $v ? ' aria-current="page"' : '' ?>><small>v<?= $n ?></small><?= e($titulo) ?></a></li>
<?php endforeach; ?>
  </ol>
  <p class="vdesc"><?= e($versiones[$v][1]) ?></p>
</section>

<section aria-labelledby="diario">
  <h2 id="diario">Lo que aprendí cada día</h2>
<?php if ($v >= 3): ?>
  <p class="sub">Siete días, siete conceptos. Cada tarjeta la genera un <code>foreach</code>.</p>
  <ol class="grid">
<?php foreach ($dias as [$d, $titulo, $texto, $codigo]): ?>
    <li class="day">
      <div class="txt"><span class="chip"><?= e($d) ?></span><h3><?= e($titulo) ?></h3><p><?= e($texto) ?></p></div>
      <div class="win"><pre><code><?= e($codigo) ?></code></pre></div>
    </li>
<?php endforeach; ?>
  </ol>
<?php else: ?>
  <p>Día 1: instalar PHP. Día 2: variables. Día 3: arrays. Día 4: funciones. Día 5: formularios. Día 6: sesiones. Día 7: juntarlo todo.</p>
  <p class="vacio">Con el bucle <code>foreach</code> de la v3 esto se convierte en tarjetas con código.</p>
<?php endif; ?>
</section>

<section id="preguntas" aria-labelledby="pq">
<?php if ($v >= 4): ?>
  <h2 id="pq">Preguntas para la clase</h2>
  <p class="sub">Escribe una pregunta sobre PHP: viaja por <code>$_POST</code> y se guarda en la sesión.</p>
  <div class="cols">
    <form method="post" action="?v=<?= $v ?>#preguntas">
      <?php if ($error): ?><p class="err" role="alert"><?= e($error) ?></p><?php endif; ?>
      <label>Tu nombre<input name="nombre" maxlength="40" required></label>
      <label>Tu pregunta<textarea name="pregunta" rows="3" maxlength="200" required></textarea></label>
      <button type="submit">Enviar pregunta</button>
    </form>
    <ul class="preg">
<?php foreach (array_reverse($_SESSION['preguntas']) as $p): ?>
      <li><b><?= e($p['nombre']) ?></b> <small><?= e($p['hora']) ?></small><br><?= e($p['pregunta']) ?></li>
<?php endforeach; ?>
<?php if (!$_SESSION['preguntas']): ?>
      <li class="vacio">Todavía no hay preguntas. Escribe la primera.</li>
<?php endif; ?>
    </ul>
  </div>
<?php else: ?>
  <h2 id="pq">Preguntas para la clase</h2>
  <div class="lock"><b>Bloqueado hasta la v4.</b> Aquí irá el formulario, con <code>$_POST</code> y sesiones.</div>
<?php endif; ?>
</section>

<?php if ($v >= 5): ?>
<section>
  <h2>Siguiente reto: base de datos</h2>
  <p class="sub">Ahora las preguntas desaparecen al cerrar el navegador porque viven en la sesión. El plan es guardarlas en MySQL con PDO y consultas preparadas.</p>
  <div class="win"><div class="bar"><i></i><i></i><i></i><span>plan.php</span></div><pre><code><?= e('$pdo = new PDO($dsn, $user, $pass);' . "\n" . '$st = $pdo->prepare("INSERT INTO preguntas (nombre, texto) VALUES (?, ?)");' . "\n" . '$st->execute([$nombre, $texto]);') ?></code></pre></div>
</section>
<?php endif; ?>

<footer><code>?&gt;</code> Hecha con PHP <?= e(PHP_VERSION) ?>  · versión v<?= $v ?></footer>
</main>
</body>
</html>