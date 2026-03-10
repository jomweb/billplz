# Billplz 6 Release Docs Design

## Goal

Prepare concise release-facing docs for Billplz 6.0.0.

## Scope

1. Refresh `README.md`
2. Add `CHANGELOG-6.x.md`
3. Add a short maintainer release doc
4. Keep wording short
5. Prefer bullets over long paragraphs

## Decisions

1. Keep the README structure mostly intact
2. Tighten the opening copy and install notes
3. Add a short upgrade section near the top
4. Write `6.0.0` changelog notes in a simple release format
5. Add one maintainer checklist doc for push, PR, tag, and release steps

## Content Rules

1. State PHP `8.3+`
2. State Billplz 6 uses `Money\Money`
3. State Codex internals are bundled
4. State Pest and current QA flow briefly
5. Avoid marketing-heavy copy

## Non-Goals

1. No full README rewrite
2. No example-by-example rewrite unless needed for correctness
3. No release tag yet
4. No upstream PR until branch strategy is clear
