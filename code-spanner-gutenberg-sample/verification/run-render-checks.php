<?php
// Run only from the disposable local Playground blueprint, never on a client site.
defined( 'ABSPATH' ) || exit;
$checks = array();
function csp_check( $name, $passed ) {
    global $checks;
    $checks[] = array( 'name' => $name, 'passed' => (bool) $passed );
}
function csp_render_sample( $attributes = array() ) {
    return render_block( array(
        'blockName' => 'code-spanner/service-notice',
        'attrs' => $attributes,
        'innerBlocks' => array(),
        'innerHTML' => '',
        'innerContent' => array(),
    ) );
}

$type = WP_Block_Type_Registry::get_instance()->get_registered( 'code-spanner/service-notice' );
csp_check( 'Block registered on server', null !== $type );
$default = csp_render_sample();
csp_check( 'Defaults render availability and no empty contact link', false !== strpos( $default, 'Open for enquiries' ) && false === strpos( $default, '<a ' ) );
$invalid = csp_render_sample( array( 'status' => 'bad-status' ) );
csp_check( 'Unknown availability falls back safely', false !== strpos( $invalid, 'csp-notice--available' ) && false === strpos( $invalid, 'bad-status' ) );
$wrong_type = csp_render_sample( array( 'status' => array( 'invalid' ) ) );
csp_check( 'Invalid attribute type gets schema default', false !== strpos( $wrong_type, 'csp-notice--available' ) );
$malicious = csp_render_sample( array(
    'title' => '<img src=x onerror=alert(1)>Safe title',
    'message' => '<script>alert(1)</script>Hello<br>Again',
    'linkUrl' => 'javascript:alert(1)',
    'linkLabel' => 'Contact',
) );
csp_check( 'HTML injection removed and unsafe URL omitted', false === strpos( $malicious, '<script' ) && false === strpos( $malicious, '<img' ) && false === strpos( $malicious, 'onerror' ) && false === strpos( $malicious, '<a ' ) );
csp_check( 'RichText line break retained', false !== strpos( $malicious, '<br>' ) );
$valid = csp_render_sample( array( 'linkUrl' => 'https://example.org/contact?one=1&two=2', 'linkLabel' => '<Contact>' ) );
csp_check( 'HTTPS link and escaped label render', false !== strpos( $valid, 'href="https://example.org/contact?' ) && false !== strpos( $valid, '&lt;Contact&gt;' ) );
$no_label = csp_render_sample( array( 'linkUrl' => 'https://example.org/contact', 'linkLabel' => ' ' ) );
csp_check( 'Blank link label omits button', false === strpos( $no_label, '<a ' ) );
$unicode = csp_render_sample( array( 'title' => 'Réparation & conseils — café' ) );
csp_check( 'Unicode text survives rendering', false !== strpos( html_entity_decode( strip_tags( $unicode ), ENT_QUOTES, 'UTF-8' ), 'Réparation & conseils — café' ) );
$mailto = csp_render_sample( array( 'linkUrl' => 'mailto:demo@example.org', 'linkLabel' => 'Email' ) );
csp_check( 'Mailto is supported', false !== strpos( $mailto, 'href="mailto:demo@example.org"' ) );
$dependencies_ok = false;
foreach ( $type->editor_script_handles as $handle ) {
    $script = wp_scripts()->registered[ $handle ];
    $dependencies_ok = in_array( 'wp-blocks', $script->deps, true ) && in_array( 'wp-block-editor', $script->deps, true );
}
csp_check( 'Editor script declares WordPress dependencies', $dependencies_ok );

$report = array( 'wordpress' => get_bloginfo( 'version' ), 'php' => PHP_VERSION, 'checkedAtUtc' => gmdate( 'c' ), 'checks' => $checks );
$report['passed'] = count( array_filter( $checks, function ( $check ) { return $check['passed']; } ) ) === count( $checks );
file_put_contents( '/csp-verification/render-checks.json', json_encode( $report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . "\n" );
if ( ! $report['passed'] ) {
    throw new RuntimeException( 'One or more service notice checks failed. See render-checks.json.' );
}

$content = '<!-- wp:paragraph --><p>Self-owned Code &amp; Spanner work sample. Fictional service message; no client data, production deployment or paid customer is represented.</p><!-- /wp:paragraph -->';
$content .= serialize_block( array(
    'blockName' => 'code-spanner/service-notice',
    'attrs' => array(
        'title' => 'A little update before you get in touch',
        'message' => 'This is an example service notice.<br>Edit the message and availability in the block editor.',
        'status' => 'limited',
        'linkLabel' => 'View the example contact link',
        'linkUrl' => 'https://example.org/contact',
    ),
    'innerBlocks' => array(), 'innerHTML' => '', 'innerContent' => array(),
) );
$id = wp_insert_post( wp_slash( array( 'import_id' => 100, 'post_type' => 'page', 'post_title' => 'Service notice — Gutenberg sample', 'post_name' => 'service-notice-demo', 'post_status' => 'publish', 'post_content' => $content ) ) );
file_put_contents( '/csp-verification/demo-page.json', json_encode( array( 'id' => $id, 'url' => get_permalink( $id ) ) ) . "\n" );
