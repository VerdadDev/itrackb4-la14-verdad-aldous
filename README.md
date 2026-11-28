#Answers

1 Your project answered one question last week and a second one this week. Name both questions, say which one your policy answers, and point at the file that answers the other.

    Last week's question was authentication: "who are you?" This week's is authorisation: "are you allowed to do this?" ComicPolicy answers the second. The first is answered in routes/web.php, where the auth middleware sits on the routes, with login code.

2 role is not in your User model's $fillable, and yet you set it and saved it in Tinker. Explain why both of those are true, and say what $fillable is actually guarding you against.

    $fillable only blocks mass assignment (like User::create($request->all())). In Tinker you set role directly and saved, so $fillable didn't apply. It protects you from users sneaking in extra fields, like role=admin, through a form.

3 signed-in non-admin typing your create address gets a 403. A guest typing the same address gets your login page. Say which layer produced each response, and why the two are deliberately different.

    The non-admin gets a 403 from the policy (authorisation): "I know who you are, but you're not allowed." The guest gets redirected by the auth middleware (authentication): "I don't know who you are, so log in first." They're different because logging in can fix the guest's problem, but not the non-admin's.

4 Your list and detail pages are still open to everybody, and your policy says so. Explain why that is a decision and not something you forgot.

    Visitors are meant to browse the library, so viewAny and view return true on purpose. It's written in the policy, so it's a choice, not something I forgot.

5 The same rule is read by your controller and by your list page. What would go wrong if you had written that role check in both places by hand instead of in a policy?

    If I wrote the role check by hand in both places, I'd have to change both whenever the rule changes. I could forget one, and the page and the controller would disagree. A policy keeps the rule in one place.

6 What does SESSION_LIFETIME count, what does it not count, and why was there no warning before you found yourself logged out?

    It counts minutes of inactivity since the last request. It does not count time since login. There was no warning because the server can't tell the browser anything until the next request arrives, and by then the session has already expired.
