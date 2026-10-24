"""Exercise installation against temporary fixture files, never a live site."""
import hashlib
import os
from pathlib import Path
import stat
import subprocess
import tempfile

script = Path(__file__).with_name("install.sh")
source = script.read_text().split("<<'HEARTFUL_WPLIST_SOURCE_20261025'\n", 1)[1].split("HEARTFUL_WPLIST_SOURCE_20261025\n", 1)[0].encode()

with tempfile.TemporaryDirectory(prefix="heartful-wplist-install-") as temporary:
    base = Path(temporary)
    root = base / "site"
    root.mkdir()
    (root / "wp-load.php").write_text("<?php // fixture only\n")
    target = root / "wplist.php"
    original = b"<?php // original fixture\n"
    target.write_bytes(original)
    target.chmod(0o640)
    checker = base / "php84-fixture"
    checker.write_text("#!/bin/sh\n[ \"$1\" = -l ] && [ -s \"$2\" ]\n")
    checker.chmod(0o700)
    backups = base / "backups"
    env = dict(os.environ, HEARTFUL_WPLIST_ROOT=str(root),
               HEARTFUL_WPLIST_BACKUP_DIR=str(backups), HEARTFUL_WPLIST_PHP_BIN=str(checker))

    def run(checked_script=script):
        return subprocess.run(["bash", str(checked_script)], env=env, capture_output=True, text=True)

    result = run()
    assert result.returncode == 0, result.stderr
    assert target.read_bytes() == source
    assert stat.S_IMODE(target.stat().st_mode) == 0o640
    saved = list(backups.glob("*.php"))
    assert len(saved) == 1 and saved[0].read_bytes() == original
    assert not list(root.glob(".wplist-stage.*"))
    result = run()
    assert result.returncode == 0 and "already current" in result.stdout
    assert len(list(backups.glob("*.php"))) == 1

    target.write_bytes(original)
    checker.write_text("#!/bin/sh\nexit 1\n")
    result = run()
    assert result.returncode != 0 and target.read_bytes() == original
    assert not list(root.glob(".wplist-stage.*"))
    checker.write_text("#!/bin/sh\nexit 0\n")

    target.unlink()
    protected = base / "protected.php"
    protected.write_bytes(original)
    target.symlink_to(protected)
    assert run().returncode != 0 and protected.read_bytes() == original
    target.unlink()
    target.write_bytes(original)

    altered = base / "altered-install.sh"
    altered.write_text(script.read_text().replace("Heartful public blog updates.", "Altered public blog updates.", 1))
    assert run(altered).returncode != 0 and target.read_bytes() == original
    assert not list(root.glob(".wplist-stage.*"))
    (root / "wp-load.php").unlink()
    assert run().returncode != 0 and target.read_bytes() == original

print("PASS: backup, exact payload, permissions, repeat, lint failure, symlink, checksum, missing WP root, cleanup")
print("Payload SHA-256:", hashlib.sha256(source).hexdigest())
