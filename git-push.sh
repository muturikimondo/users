#!/bin/bash

REPO_NAME="users"
GITHUB_USER="muturikimondo"
REMOTE_URL="https://github.com/$GITHUB_USER/$REPO_NAME.git"

# Step 0: Optional Git identity setup
git config --global user.name > /dev/null || git config --global user.name "Muturi Kimondo"
git config --global user.email > /dev/null || git config --global user.email "muturikimondo@gmail.com"

# Step 1: Create README
echo "# $REPO_NAME" > README.md

# Step 2: Initialize Git (if not already)
if [ ! -d .git ]; then
    git init
else
    echo "Git already initialized."
fi

# Step 3: Add README
git add README.md
git commit -m "first commit"

# Step 4: Set branch to main
git branch -M main

# Step 5: Check if remote exists
if git remote get-url origin 2>/dev/null; then
    echo "Remote 'origin' exists. Updating to HTTPS..."
    git remote set-url origin "$REMOTE_URL"
else
    echo "Adding new remote 'origin'..."
    git remote add origin "$REMOTE_URL"
fi

# Step 6: Push
git push -u origin main
