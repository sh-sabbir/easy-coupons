# Easy Coupons

A WordPress plugin to lock video with coupon code.

## Usage

```
- Create new locked video from "Dashboard -> Easy Videos"
  - Enter Video title
  - Enter Video url (only youtube/vimeo or other streaming embeed link will work)
    - no support for self hosted video
  - Select featured image. (It will be shown when video is locked.)
  - Publish.

- Copy the short code and it can be used anywhere to show a locked video.
```

## Requirements (with estimation)


- [x] Create a plugin called "Easy Coupons" with the following features:
  - [x] As an admin, I should be able to bulk generate any quantity of randomly generated, unique 4 character alpha-numeric coupon codes (ex. f5Ba, 891d, etc.) - **3 hours**
  - [x] As an admin, I should be able to search and delete any individual coupon code - **1 hours**
  - [x] As an admin, I should be able to specify an expiry date when I generate coupon codes  - **1 hours**
  - [x] As an admin, I should be able to bulk delete coupons based on expiry date  - **1.5 hours**
- [x] Create a page with 3 gated educational YouTube videos
  - [x] As a visitor, to unlock and display a single video I should be able to enter a single valid coupon code (found in the database and future expiry) - **2 hours**
  - [x] As a visitor, I should receive an error message if I attempt to use the same coupon code more than once - **0.5 hours**
  - [x] As a visitor, I should continue to have access to a video after I enter a valid coupon code (even if I close the browser and return to the site) - **1 hours**
  - [x] As an admin, I should be able to see which video a coupon code was applied to - **2 hours**
  - [x] As an admin, I should see a report of failed coupon code validations with two categories: not found & already used  - **3 hours**
  - [x] As an admin, I should be able to use the coupon code ADMN unlimited times to access any video  - **1 hours**
- [ ] Testing - **4 hours**
  - [ ] As a developer, I should be able to test any business logic with automated unit tests
- [x] Styling
  - [x] Create a custom WP theme with the stylesheet of your choice - **2 hours**
  - [x] Add animation to the success and failure states of entering a coupon - **1 hours**
- [x] Documentation - **4 hours**
  - [x] Add code comments as needed to improve the ability for another developer to finish this project
  - [x] Update the README with a brief written summary of what's been completed and what still needs to be done
  - [x] Update the README Any other feedback including which features could be refactored or improved

## Not complete
- Test cases

## Other feedbacks
- `WP_LIST_TABLE` can have screen options to improve experience. 
- A `scheduled event` to automatically check and update expired coupon status.
- Possible to hook the locking machanism to any post or page or custom post type.