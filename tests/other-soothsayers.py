"""Run with python3 tests/other-soothsayers.py; PHP_BIN may select the PHP CLI."""
import json, os, pathlib, sqlite3, subprocess
root = pathlib.Path(__file__).resolve().parents[1]
php = os.environ.get('PHP_BIN', 'php')
# Use the production renderer without loading DB credentials or opening a connection.
source = (root / 'themes/test/inc/mcast-top.php').read_text()
renderer = source[source.index('$createCard = static function'):]
renderer = renderer[:renderer.index('\n\t};') + len('\n\t};')]
harness = '''<?php
const USER_IMG3 = '/portraits/'; const USER_IMG2 = '/status/';
const USER_URL = '/Public/'; const TENPO_ID = 'ALL';
function mysqli_real_escape_string($link, $value) { return $value; }
function mysqli_query($link, $sql) {
    $GLOBALS['sql'] = $sql;
    return (object) ['rows' => [
      ['mkanteishi_id'=>1, 'name'=>'お休み<&先生', 'tenponame'=>'', 'img'=>'', 'imgname'=>''],
      ['mkanteishi_id'=>8, 'name'=>'リモート待機', 'tenponame'=>'', 'img'=>'', 'imgname'=>'']
    ]];
}
function mysqli_fetch_assoc($result) { return array_shift($result->rows); }
function mysqli_free_result($result) {}
''' + renderer + '\nrequire ' + json.dumps(str(root/'themes/test/inc/other-soothsayers.php')) + ''';
$html = heartful_other_soothsayers(null, '2026-09-29', [8=>true], $createCard);
echo json_encode(['sql'=>$GLOBALS['sql'], 'html'=>$html], JSON_UNESCAPED_UNICODE);
'''
result = subprocess.run([php, '-n'], input=harness, text=True, capture_output=True, check=True)
data = json.loads(result.stdout)
db = sqlite3.connect(':memory:')
db.executescript('''
CREATE TABLE mkanteishis (id INTEGER, name TEXT, yomigana TEXT, delflg INTEGER);
CREATE TABLE mcast (mkanteishi_id INTEGER, mdivision_id INTEGER);
CREATE TABLE eworkdays (id INTEGER, mkanteishi_id INTEGER, workdate TEXT, holiday_flg INTEGER);
INSERT INTO mkanteishis VALUES
 (1,'off','a',0),(2,'today','b',0),(3,'retired','c',1),(4,'holiday','d',0),
 (5,'test','e',0),(6,'tomorrow','f',0),(7,'other division','g',0),(8,'remote','h',0),
 (9,'finished today','i',0),(10,'null deleted flag','j',NULL);
INSERT INTO mcast VALUES (1,1),(1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,2),(8,1),(9,1),(10,1);
INSERT INTO eworkdays VALUES (1,2,'2026-09-29',0),(2,4,'2026-09-29',1),
 (3,6,'2026-09-30',0),(-8,8,'2000-01-01',0),(4,9,'2026-09-29',0);
''')
ids = [r[0] for r in db.execute(data['sql'])]
assert ids == [1,4,6,8], ids
html = data['html']
assert 'お休み&lt;&amp;先生' in html
assert 'リモート待機' not in html
assert '<details class="other-soothsayers">' in html and '<details open' not in html
assert 'loading="lazy"' in html and '/status/' not in html
assert '本日の出演はありません' in html
assert html.count('<li>') == 1
print('PASS: SQL excludes retired, today, finished-today, test and other-division teachers; holiday/future teachers included once. Shared renderer escapes names; remote duplicates excluded; disclosure initially closed.')
if os.environ.get('PREVIEW_HTML'):
    pathlib.Path(os.environ['PREVIEW_HTML']).write_text('<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="/themes/test/commons/reset.css"><link rel="stylesheet" href="/themes/test/commons/style.css"><div class="bg_gradation"><div class="soothsayer"><section class="content"><h2>本日の占い師</h2>'+html+'</section></div></div>')
