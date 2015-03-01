This module allows your users to add a mixed document and link library to spaces and user profiles. Libraries are organized in categories, which may selectively be marked public. Only publishers/admins may add content to public categories.

Optionally, a global public library can be added to the top menu. This library contains all public material of the site grouped by originating space.

## Features

- Create collections (categories) and collaboratively add documents and links
- Fancy icons clearly depicting the file types (mime) and links
- Reorder categories and items using drag and drop
- New library content will be posted to the space/user stream
- Communicate about library content with other users (comment, like)
- Verify links using an extended connection test, courtesy of __Sebastian Stumpf__ (per-space config option)
- Show selected categories and their contents in a space-wide sidebar widget (per-space config option)
- Require publisher/admin rights also for adding non-public content (per-space config option)
- Enable global public library (per-site config option)
- Add custom disclaimer text to the global public library (per-site config option)

## Internationalization (i18n)

This module is currently available in

- English (en)
- German (de)

## Todo

- Clean up the !$*@%** mess handling the file upload. File dialog should immediately open when clicking the "add document"
  button. It should either only allow to upload a single file or it should handle multiple uploaded files gracefully by
  creating one document record per file. After uploading the file(s), a form should ask for titles, dates and description
  per document.
- Uploaded files need to stay bound to the not-yet persisted record in case the record doesn't validate.
- When we remove a file from a document it is immediately deleted even if we don't save the document afterwards. Find a
  better way to do this. Best option would be to disallow deletion completely and only allow swapping/updating the file.
- Think of a smart way to swap/update the file associated with a document. Do we need a new wall entry for that?
- Move items between categories in a space, preferably by drag and drop.
- Move items to a foreign library/category a user has access to.
- Create a context sensitive activity sidebar. In global public library, it should only display activity concerning public
  content of the currently selected space. In a user/space library, it should only display activity concerning any library
  content. Stop using the current activity widget or fix the broken links on non-stream pages.
- Reference library items from a new post.
- Support notifying selected users/groups about a new library item.
- i18n of the DeadLibraryLinkValidator failure message.

## Acknowledgement

__Author:__ Matthias Wolf

This module is based extensively on the work of __Sebastian Stumpf__ in his __Link List__ module.

