# WordPress Developer Test

This is a test project to assess a software developer's skills. This test is a form of [Kobayashi Maru scenario](https://en.wikipedia.org/wiki/Kobayashi_Maru). (Yes, some of us are dorks).

## Tips for passing this test

- Use simple frameworks instead of bloated or complicated ones.
- Ask us if you're unsure about something.
- We believe it is impossible to finish this job completely in 8 hours. Do the best you can - your effort and approach are being tested. We are also trying to understand how you wrap up a project temporarily before it is fully complete.
- It's better to de-risk the project by finishing the most complex aspects first.
- It's better to stop short and provide a well documented handoff rather than working right up until the time limit.

## Requirements

[ ] When you start the test, create a GitHub repo and grant access to `pritpalbbemarketing`
  [ ] Create a README with an estimate for each of the individual tasks below
[ ] Create a plugin called "Easy Coupons" with the following features:
  [ ] As an admin, I should be able to bulk generate any quantity of randomly generated, unique 4 character alpha-numeric coupon codes (ex. f5Ba, 891d, etc.)
  [ ] As an admin, I should be able to search and delete any individual coupon code
  [ ] As an admin, I should be able to specify an expiry date when I generate coupon codes
  [ ] As an admin, I should be able to bulk delete coupons based on expiry date
[ ] Create a page with 3 gated educational YouTube videos
  [ ] As a visitor, to unlock and display a single video I should be able to enter a single valid coupon code (found in the database and future expiry)
  [ ] As a visitor, I should receive an error message if I attempt to use the same coupon code more than once
  [ ] As a visitor, I should continue to have access to a video after I enter a valid coupon code (even if I close the browser and return to the site)
  [ ] As an admin, I should be able to see which video a coupon code was applied to
  [ ] As an admin, I should see a report of failed coupon code validations with two categories: not found & already used
  [ ] As an admin, I should be able to use the coupon code ADMN unlimited times to access any video
[ ] Testing
  [ ] As a developer, I should be able to test any business logic with automated unit tests
[ ] Styling
  [ ] Create a custom WP theme with the stylesheet of your choice
  [ ] Add animation to the success and failure states of entering a coupon
[ ] Documentation
  [ ] Add code comments as needed to improve the ability for another developer to finish this project
  [ ] Update the README with a brief written summary of what's been completed and what still needs to be done
  [ ] Update the README Any other feedback including which features could be refactored or improved