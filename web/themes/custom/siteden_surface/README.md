# SiteDen Surface Drupal Theme

## Refactoring – July 2022

The SiteDen Surface theme was originally built to pull assets from the SiteDen Pattern Lab repository. We decided to convert the theme to align with the UCLA Web Component Library (see https://webcomponents.ucla.edu/). Refactoring the theme will be a months-long effort. During this process, we will leave the original Pattern Lab files in place, include the new Web Component Library, and gradually convert individual elements from Pattern Lab to Component Library. We are also considering architectural changes that Drupal 10 and CKEditor 5 will require as we begin this process.

### 1. SiteDen Pattern Lab (deprecated)

The first step of this refactoring process was to move Pattern Lab assets into the folder `siteden_surface/siteden-patternlab`, and include those in the repository as a static asset. That is, the Pattern Lab files are no longer compiled as part of the artifact build process, and the distribution files are committed directly in the SiteDen repository. This saves on build time, and also allows us to migrate to more modern versions of build tools without having to modify the original Pattern Lab installation. If you need to make changes to Pattern Lab files, do so in the siteden-patternlab folder, and recompile by running `npm ci` (Node v14 max) and commit the resulting changes to the repository. We will eventually remove this library in its entirety from the SiteDen Surface theme.

### 2. UCLA Web Component Library

We have included the UCLA Web Component Library files in the folder `siteden_surface/ucla-bruin-components`. This is currently a static copy of the repository representing a specific tag. We will begin to include the compiled CSS and JavaScript in the SiteDen theme and gradually apply styling to components as development resources allow.

### 3. SiteDen custom theme

The UCLA Web Component Library provides definitions for many components, but there is a need for a custom theme for Drupal elements that are not included, or for legacy compatibility with SiteDen. We will attempt to use SASS definitions from the UCLA Web Component Library, such as color variables, breakpoints, and other utilities as much as possible, but recognize that the SiteDen theme will deviate from the Componnt Library at times.

### 4. SiteDen Component module

We created a separate custom module to house component templates outside the SiteDen Surface theme (see `modules/custom/siteden_components`). This should allow us to utilize components in multiple themes or using different layout approaches without a dependency on the SiteDen Surface theme itself.
