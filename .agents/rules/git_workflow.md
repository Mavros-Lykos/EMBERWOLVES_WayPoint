---
description: Enforces strict open-source git branching, issue tracking, and commit conventions for all agents and human contributors.
trigger: always_on
---

# Git Workflow & Version Control Guidelines
*Open Source Standard Operating Procedure*

To ensure clean collaboration, prevent merge conflicts, and maintain a pristine history, all AI agents and human contributors MUST follow this standard open-source Git workflow.

## 1. Issue Tracking (The Absolute First Step)
**Rule: No code is written without a corresponding Issue.**
Before creating a branch or writing any code, an Issue must be defined (e.g., in GitHub, Jira, or a local issue tracker document).
*   **Issue Title:** Must clearly state the goal (e.g., `[Feature] Store Receiving Page`).
*   **Issue Description:** Must include Acceptance Criteria, Target Persona, and Design Theme context.
*   **Issue ID:** Every issue must have a unique identifier (e.g., `#12` or `WD-04`).

## 2. Branching Strategy (No Direct Commits to Main)
**Rule: Never commit directly to the `main` or `master` branch.** 
For every new page, screen, feature, or bug fix, a dedicated branch must be created tied directly to the Issue ID.

### Branch Naming Convention
Branches must be named using the format: `<type>/<issue-id>-<short-description>`
*   `feat/`: For new screens, pages, or major features (e.g., `feat/12-store-receiving-page`, `feat/04-auth-screen`).
*   `fix/`: For bug fixes or UI corrections (e.g., `fix/15-sys-01-contrast`).
*   `docs/`: For updates to READMEs, guidelines, or sitemaps.
*   `refactor/`: For restructuring code without changing behavior.
*   `chore/`: For updating dependencies, tooling, or minor project configurations.

**Example Command:**
`git checkout -b feat/12-driver-pwa-screen`

## 3. Page-by-Page Isolation
*   **One Branch = One Issue:** Do not build the entire app in one branch. Keep the scope strictly limited to the Acceptance Criteria of the Issue.
*   If you need to edit global CSS (`styles.css`), do it in the branch of the page that requires the change, but keep the scope tight.

## 4. Commit Message Standards (Conventional Commits)
All commit messages must follow the [Conventional Commits](https://www.conventionalcommits.org/) specification and reference the Issue ID to ensure an automated, readable history.

**Format:**
`<type>(<optional scope>): <description> (Closes #<issue-id>)`

**Examples:**
*   `feat(auth): implement serene light theme on login screen (Closes #04)`
*   `fix(css): resolve white background bug in sys-01 (Closes #15)`
*   `docs(rules): add git workflow and branching guidelines`

## 5. Pull Requests / Merging
*   Once a branch satisfies the Issue's Acceptance Criteria, push the branch to the remote repository.
*   Open a Pull Request (PR) against the `main` branch.
*   **PR Title:** Must match the Issue title.
*   **PR Description:** Must include a clear summary of changes, screenshots of UI changes (showing Light/Dark modes if applicable), and the phrase `Closes #<issue-id>` to automatically close the tracking issue upon merge.

## 6. Absolute Agent Instructions for Git
If the user asks the AI Agent to build a feature:
1. **Verify Issue:** The Agent must ask the user for the Issue ID or context.
2. **Branch Creation:** The Agent MUST proactively execute or prompt the user to run `git checkout -b <type>/<issue-id>-<name>`.
3. **Commit:** After finishing the work, the Agent should run `git add .` and `git commit` using the strict Conventional Commits format referencing the Issue ID.
