# Billplz 6 Release Docs Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Refresh release-facing docs for Billplz 6.0.0 with concise README copy, a new changelog, and a short maintainer release checklist.

**Architecture:** Keep the repo structure stable. Update the top release-facing sections in `README.md`, add a dedicated `CHANGELOG-6.x.md`, and add one short maintainer doc under `docs/`. Avoid broad example rewrites unless a line is stale or misleading.

**Tech Stack:** Markdown, Composer, GitHub Actions, Pest, PHPStan, Laravel Pint

---

### Task 1: Refresh the README release copy

**Files:**
- Modify: `README.md`

**Step 1: Update the opening notice**

Replace the current branch notice with short Billplz 6 release copy.

**Step 2: Tighten install and upgrade notes**

Add short bullets for PHP `8.3+`, `Money\Money`, bundled Codex internals, and package requirements.

**Step 3: Remove stale wording**

Keep the README concise and fix any release-facing lines that do not match the current package state.

**Step 4: Verify the diff**

Run: `git diff -- README.md`
Expected: Only release-facing copy changes.

### Task 2: Add the 6.x changelog

**Files:**
- Create: `CHANGELOG-6.x.md`

**Step 1: Add a `6.0.0` entry**

Use short sections for `Added`, `Changed`, `Removed`, `Internal`, and `Upgrade Notes`.

**Step 2: Keep bullets user-focused**

Lead with runtime, dependency, and migration impact.

**Step 3: Verify the file**

Run: `sed -n '1,220p' CHANGELOG-6.x.md`
Expected: Concise release notes for `6.0.0`.

### Task 3: Add a maintainer release checklist

**Files:**
- Create: `docs/release-6.0.0.md`

**Step 1: Add a short numbered checklist**

Cover QA, push, PR, tag, GitHub release notes, and Packagist expectations.

**Step 2: Keep branch constraints explicit**

Note that upstream release work depends on the existence of an upstream `6.x` branch.

**Step 3: Verify the file**

Run: `sed -n '1,220p' docs/release-6.0.0.md`
Expected: Clear, short maintainer steps.

### Task 4: Verify docs and package health

**Files:**
- Test: `README.md`
- Test: `CHANGELOG-6.x.md`
- Test: `docs/release-6.0.0.md`

**Step 1: Run doc-focused checks**

Run: `git diff --stat`
Expected: README, changelog, release docs, and plan docs only.

**Step 2: Run package verification**

Run: `composer validate --strict && composer run qa`
Expected: All checks pass.

**Step 3: Commit**

```bash
git add README.md CHANGELOG-6.x.md docs/release-6.0.0.md docs/plans/2026-03-10-release-docs-design.md docs/plans/2026-03-10-release-docs-implementation-plan.md
git commit -m "docs: prepare Billplz 6 release notes"
```
