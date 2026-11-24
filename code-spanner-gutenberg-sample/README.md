# Service notice — Gutenberg work sample

A self-owned Code & Spanner demonstration created on 23 November 2026 with AI assistance. This is not commissioned client work, a historical case study, or a claim of production deployment.

This small plugin adds an editable service notice to WordPress. A site editor can change the heading, message, availability and optional contact link without touching code. The front end is rendered in PHP, while the editor uses the native Gutenberg React components. No frontend JavaScript or external service is required.

## Implementation

- Shared block metadata in `block.json`, registered on `init` in PHP.
- Native `RichText`, `InspectorControls`, `PanelBody`, `SelectControl` and `TextControl` editor UI.
- Dynamic server render with filtered RichText (line breaks only), escaped link labels and restricted URL protocols.
- Availability is allowlisted; unexpected stored values fall back to the default.
- Contact link omitted when the URL or label is empty or the URL protocol is disallowed.
- Human-readable status labels; keyboard focus styling; responsive layout.
- No credentials, client data, external requests, database tables or automated business actions.

## Try locally

Install Node.js 20.18 or newer. From this directory run:

```sh
npx --yes @wp-playground/cli@latest server --mount=./service-notice:/wordpress/wp-content/plugins/service-notice --mount=./verification:/csp-verification --blueprint=blueprint.json --port=9413 --workers=1
```

The blueprint activates the plugin, runs the render checks and creates an explicitly labelled demonstration page. Use the block inserter to find **Service notice**, edit the content and settings, save the page, then view the front end.

This command downloads WordPress and the official Playground runtime. It uses a disposable local site; it does not connect to a customer site. The demonstration has no production hosting or ongoing service costs.

## Boundaries

This is a focused code sample, not a complete site or a general security/accessibility certification. It has no expiry scheduler, REST endpoint, external integration, customer notification or SLA enforcement. Availability is a manually chosen message. Production use would need its own scope, testing and review.

The accompanying verification record reports the exact environment and checks actually performed. Do not describe unrun checks as passing.

## Verification limits

Eleven server-rendering checks passed on the environment recorded in `verification/verification-summary.json`, and the front-end page was inspected in a browser. The visual editor canvas was blank in the testing browser, so interactive block editing and saving have **not** been verified. A mobile viewport was not successfully applied; responsive behavior remains unverified. This is a new AI-assisted source demonstration for review, not production-ready delivery or a client reference.
