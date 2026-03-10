# CI Alignment And Release Drafts Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Align GitHub Actions with local Composer QA scripts and prepare concise PR and release-note drafts for the upstream 6.x PR and 6.0.0 release.

**Architecture:** Route workflow checks through Composer scripts so local hooks and CI use the same entry points. Keep matrices on supported PHP versions and add two short markdown drafts for the upstream PR and GitHub release notes.

**Tech Stack:** GitHub Actions, Composer, Pest, PHPStan, Laravel Pint, Markdown

---

### Task 1: Align CI entry points

**Files:**
- Modify: `.github/workflows/analyse.yml`
- Modify: `.github/workflows/tests.yml`
- Modify: `.github/workflows/coveralls.yml`

**Step 1: Replace raw commands with Composer scripts**

Use `composer run lint`, `composer run analyse`, and `composer run test`.

**Step 2: Keep supported PHP matrix values**

Ensure the workflows match the package support window on the 6.x branch.

**Step 3: Verify workflow diff**

Run: `git diff -- .github/workflows`
Expected: CI-only changes.

### Task 2: Add PR and release drafts

**Files:**
- Create: `docs/pr-jomweb-billplz-6x.md`
- Create: `docs/release-notes-v6.0.0.md`

**Step 1: Draft the upstream PR title and body**

Keep it concise. Cover breaking changes, CI/tooling, and verification.

**Step 2: Draft the GitHub release notes**

Base the content on `CHANGELOG-6.x.md`.

### Task 3: Verify and prepare push

**Files:**
- Test: `.github/workflows/*.yml`
- Test: `docs/pr-jomweb-billplz-6x.md`
- Test: `docs/release-notes-v6.0.0.md`

**Step 1: Run package verification**

Run: `composer validate --strict && composer run qa`
Expected: All checks pass.

**Step 2: Confirm upstream target**

Run: `git ls-remote --heads upstream`
Expected: `upstream/6.x` exists and can be used as the PR target.

**Step 3: Commit**

```bash
git add .github/workflows docs/plans/2026-03-11-ci-alignment-and-release-drafts-plan.md docs/pr-jomweb-billplz-6x.md docs/release-notes-v6.0.0.md
git commit -m "ci: align workflows with composer scripts"
```
