#!/bin/bash

# Base path for styles
BASE_DIR="coop/styles"

echo "🔧 Creating modular CSS structure in $BASE_DIR..."

# Folders
folders=(
  "base"
  "layout"
  "components"
  "utilities"
  "themes"
  "pages"
)

# Corresponding files for each folder (same order as folders array)
files=(
  "reset.css variables.css typography.css"
  "auth.css dashboard.css header-footer.css"
  "forms.css select2.css buttons.css cards.css"
  "spacing.css colors.css animations.css"
  "light.css"
  "register.css users.css imperests.css"
)

# Create folders and files
for i in "${!folders[@]}"; do
  folder="$BASE_DIR/${folders[$i]}"
  mkdir -p "$folder"

  for file in ${files[$i]}; do
    touch "$folder/$file"
    echo "/* $file */" > "$folder/$file"
  done
done

# Create master styles.css with imports
cat <<EOF > "$BASE_DIR/styles.css"
/* coop/styles/styles.css */

/* Base Foundation */
@import url('./base/reset.css');
@import url('./base/variables.css');
@import url('./base/typography.css');

/* Layout */
@import url('./layout/auth.css');
@import url('./layout/dashboard.css');
@import url('./layout/header-footer.css');

/* Components */
@import url('./components/forms.css');
@import url('./components/select2.css');
@import url('./components/buttons.css');
@import url('./components/cards.css');

/* Utilities */
@import url('./utilities/spacing.css');
@import url('./utilities/colors.css');
@import url('./utilities/animations.css');

/* Themes */
@import url('./themes/light.css');

/* Page Specific */
@import url('./pages/register.css');
@import url('./pages/users.css');
@import url('./pages/imperests.css');
EOF

echo "✅ Modular CSS structure created successfully!"

