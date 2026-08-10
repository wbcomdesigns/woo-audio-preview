<?php
/**
 * Generate docs/HOOKS.md from the source.
 *
 * A hand-written hook reference goes stale the first time someone adds a filter and forgets
 * the doc - this plugin's was documenting 13 of 18. Generating it means the reference cannot
 * lie: if a hook exists, it is listed, with the file and line you can open.
 *
 * Usage: php bin/generate-hook-docs.php [plugin-dir]
 *
 * @package Wbcom
 */

$root = isset( $argv[1] ) ? rtrim( $argv[1], '/' ) : dirname( __DIR__ );
$name = basename( $root );

$skip = array( '/vendor/', '/node_modules/', '/tests/', '/lib/wbcom-settings/' );
$rii  = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root ) );
$rows = array();

foreach ( $rii as $file ) {
	if ( $file->isDir() || 'php' !== $file->getExtension() ) {
		continue;
	}
	$path = str_replace( $root . '/', '', $file->getPathname() );
	foreach ( $skip as $s ) {
		if ( false !== strpos( '/' . $path, $s ) ) {
			continue 2;
		}
	}

	$lines = file( $file->getPathname() );
	foreach ( $lines as $i => $line ) {
		if ( ! preg_match( "/(do_action|apply_filters)\(\s*'([a-z0-9_]+)'/", $line, $m ) ) {
			continue;
		}

		/*
		 * Only a docblock that IMMEDIATELY precedes the hook describes the hook. Walking up
		 * until any comment is found picks up the enclosing method's docblock instead, which
		 * is how the first run of this script attributed the loader's description to a
		 * filter about audio extensions.
		 */
		$summary = '';
		$k       = $i - 1;

		while ( $k >= 0 && '' === trim( $lines[ $k ] ) ) {
			$k--;
		}

		if ( $k >= 0 && '*/' === trim( $lines[ $k ] ) ) {
			$parts = array();
			for ( $j = $k - 1; $j >= 0 && $j > $k - 30; $j-- ) {
				$t = trim( $lines[ $j ] );
				if ( 0 === strpos( $t, '/**' ) || 0 === strpos( $t, '/*' ) ) {
					break;
				}
				$t = trim( ltrim( $t, '* ' ) );
				if ( '' !== $t && 0 !== strpos( $t, '@' ) ) {
					array_unshift( $parts, $t );
				}
			}
			$summary = implode( ' ', $parts );
		}

		$rows[ $m[2] ] = array(
			'type' => 'do_action' === $m[1] ? 'action' : 'filter',
			'file' => $path,
			'line' => $i + 1,
			'note' => $summary ? preg_replace( '/\s+/', ' ', substr( $summary, 0, 200 ) ) : '',
		);
	}
}

ksort( $rows );

$out  = "# Hooks — {$name}\n\n";
$out .= "Generated from the source by `bin/generate-hook-docs.php`. Do not edit by hand: run the\n";
$out .= "script instead, or the reference drifts from the code the way the last one did.\n\n";
$out .= sprintf( "%d extension points: %d actions, %d filters.\n\n",
	count( $rows ),
	count( array_filter( $rows, function ( $r ) { return 'action' === $r['type']; } ) ),
	count( array_filter( $rows, function ( $r ) { return 'filter' === $r['type']; } ) )
);
$out .= "| Hook | Type | Declared in | What it is for |\n|---|---|---|---|\n";

foreach ( $rows as $hook => $r ) {
	$out .= sprintf( "| `%s` | %s | `%s:%d` | %s |\n", $hook, $r['type'], $r['file'], $r['line'], $r['note'] ?: '—' );
}

if ( ! is_dir( $root . '/docs' ) ) {
	mkdir( $root . '/docs', 0755, true );
}
file_put_contents( $root . '/docs/HOOKS.md', $out );
echo "  {$name}: documented " . count( $rows ) . " hooks\n";
