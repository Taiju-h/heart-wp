// Exercise the exact PHP embedded in install.sh with an isolated WordPress fixture.
const fs = require('node:fs');
const path = require('node:path');
const assert = require('node:assert/strict');
const crypto = require('node:crypto');
const modules = process.env.PHP_WASM_ROOT;
function dependency(name) { return require(modules ? path.join(modules, name) : name); }
const { PHP } = dependency('@php-wasm/universal');
const { loadNodeRuntime } = dependency('@php-wasm/node');

(async () => {
  const installer = fs.readFileSync(path.join(__dirname, '..', 'install.sh'), 'utf8');
  const source = installer.split("<<'HEARTFUL_WPLIST_SOURCE_20261025'\n")[1]
    .split('HEARTFUL_WPLIST_SOURCE_20261025\n')[0];
  const checksum = installer.match(/wplist_hash" != "([a-f0-9]{64})"/)[1];
  assert.equal(crypto.createHash('sha256').update(source).digest('hex'), checksum);

  const php = new PHP(await loadNodeRuntime('8.4', { emscriptenOptions: { processId: 4 } }));
  php.writeFile('/internal/shared/php.ini', 'display_errors=1\nerror_reporting=32767\n');
  php.mkdir('/app');
  php.writeFile('/app/wplist.php', source);
  for (const name of ['wp-load.php', 'check.php']) {
    php.writeFile('/app/' + name, fs.readFileSync(path.join(__dirname, name)));
  }
  let result = await php.run({ code: '<?php token_get_all(file_get_contents("/app/wplist.php"), TOKEN_PARSE); echo PHP_VERSION . " syntax OK";' });
  assert.equal(result.exitCode, 0, result.text + result.errors);
  assert.equal(result.errors, '');
  console.log(result.text);

  for (const name of ['normal', 'empty', 'exception']) {
    result = await php.run({ code: '<?php $CASE=' + JSON.stringify(name) + ';require "/app/check.php";' });
    assert.equal(result.exitCode, 0, result.text + result.errors);
    assert.equal(result.errors, '', result.errors);
    assert(!/Fatal error|Warning|Deprecated|Parse error/.test(result.text), result.text);
    assert(result.text.startsWith('PASS:'), result.text);
    console.log(result.text);
    if (name === 'normal' && process.env.HEARTFUL_WPLIST_RENDER_DIR) {
      fs.mkdirSync(process.env.HEARTFUL_WPLIST_RENDER_DIR, { recursive: true });
      fs.writeFileSync(path.join(process.env.HEARTFUL_WPLIST_RENDER_DIR, 'wplist.html'), php.readFileAsBuffer('/app/rendered.html'));
    }
  }
  process.exit(0);
})().catch(error => { console.error(error); process.exit(1); });
