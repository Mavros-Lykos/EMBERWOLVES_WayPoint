---
description: Enforces strict open-source git branching and commit conventions for all agents and human contributors.
trigger: always_on
---

# Git Workflow & Version Control Guidelines
*Open Source Standard Operating Procedure*

To ensure clean collaboration, prevent merge conflicts, and maintain a pristine history, all AI agents and human contributors MUST follow this standard open-source Git workflow.

## 1. Branching Strategy (No Direct Commits to Main)
**Never commit directly to the `main` branch.** 
For every new page, screen, feature, or bug fix, a dedicated branch must be created BEFORE writing any code.

### Branch Naming Convention
Branches must be named using the following prefixes:
*   `feat/`: For new screens, pages, or major features (e.g., `feat/store-receiving-page`, `feat/auth-screen`).
*   `fix/`: For bug fixes or UI corrections (e.g., `fix/sys-01-contrast`, `fix/mobile-layout`).
*   `docs/`: For updates to READMEs, guidelines, or sitemaps.
*   `refactor/`: For restructuring code without changing behavior.
*   `chore/`: For updating dependencies, tooling, or minor project configurations.

**Example Command:**
`git checkout -b feat/driver-pwa-screen`

## 2. Page-by-Page Isolation
*   **One Branch = One Page/Screen:** Do not build the entire app in one branch. If you are building the Store Receiving page, the branch must be `feat/store-receiving`. If you are building the Dispatcher Dashboard, the branch must be `feat/dispatch-dashboard`. 
*   If you need to edit global CSS (`styles.css`), do it in the branch of the page that requires the change, but keep the scope tight.

## 3. Commit Message Standards (Conventional Commits)
All commit messages must follow the [Conventional Commits](https://www.conventionalcommits.org/) specification to ensure an automated, readable history.

**Format:**
`<type>(<optional scope>): <description>`

**Examples:**
*   `feat(auth): implement serene light theme on login screen`
*   `fix(css): resolve white background bug in sys-01`
*   `docs(rules): add git workflow and branching guidelines`

## 4. Pull Requests / Merging
*   Once a page or feature is complete, push the branch to the remote repository.
*   Open a Pull Request (PR) against the `main` branch.
*   Provide a clear summary of the changes in the PR description. If it is a UI change, attach screenshots of the Light and Dark modes (if applicable).

## 5. Agent Instructions for Git
If the user asks the AI Agent to create a new page, the Agent MUST proactively execute or prompt the user to run `git checkout -b feat/<page-name>` before generating and saving the code. After finishing the page, the Agent should run `git add` and `git commit` using the Conventional Commits format.
