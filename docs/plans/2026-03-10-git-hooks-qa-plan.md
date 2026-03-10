# Git Hooks QA Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add repo-managed git hooks that run Pint on commit and PHPStan plus Pest on push, with matching Composer scripts and contributor documentation.

**Architecture:** Keep the setup lightweight and dependency-free by storing hook scripts in `.githooks/` and activating them with `git config core.hooksPath .githooks`. Route all checks through Composer scripts so local commands, hooks, and CI share the same entry points.

**Tech Stack:** Git hooks, Composer scripts, Pint, PHPStan, Pest, Markdown docs

---

### Task 1: Add shared Composer QA commands

**Files:**
- Modify: `composer.json`

**Step 1: Add Composer scripts**

Add `lint`, `analyse`, `test`, `qa`, and `hooks:install` scripts to `composer.json`.

**Step 2: Verify the commands resolve**

Run: `composer run lint`, `composer run analyse`, `composer run test`
Expected: Existing local checks execute through Composer.

### Task 2: Add repo-managed hook scripts

**Files:**
- Create: `.githooks/pre-commit`
- Create: `.githooks/pre-push`

**Step 1: Add executable shell scripts**

Create POSIX shell hooks that change into the repo root and call the Composer scripts.

**Step 2: Mark hooks executable**

Run: `chmod +x .githooks/pre-commit .githooks/pre-push`
Expected: Git can execute both hooks after `core.hooksPath` is configured.

### Task 3: Document installation and manual usage

**Files:**
- Modify: `CONTRIBUTING.md`

**Step 1: Replace outdated test guidance**

Document `composer hooks:install`, the hook behavior, and the manual `composer lint`, `composer analyse`, `composer test`, and `composer qa` commands.

**Step 2: Keep CI guidance aligned**

State that GitHub Actions remains the final verification layer for pull requests.

### Task 4: Verify the workflow end-to-end

**Files:**
- Test: `.githooks/pre-commit`
- Test: `.githooks/pre-push`

**Step 1: Run the Composer checks**

Run: `composer run lint && composer run analyse && composer run test`
Expected: All commands succeed.

**Step 2: Run the hook scripts directly**

Run: `.githooks/pre-commit && .githooks/pre-push`
Expected: Both hook scripts succeed when executed from the repo root.

**Step 3: Inspect git diff**

Run: `git diff --stat`
Expected: Only hook, Composer, and docs changes relevant to this feature appear.
