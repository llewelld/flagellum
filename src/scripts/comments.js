function loadScript(src) {
  return new Promise((resolve, reject) => {
    if (document.querySelector("head > script[src=\"" + src + "\"]") !== null) return resolve();
    const script = document.createElement("script");
    script.src = src;
    script.async = true;
    document.head.appendChild(script);
    script.onload = resolve;
    script.onerror = reject;
  });
}

function loadComments(instance, comment_id) {
  loadScript("scripts/purify.min.js")
    .then(() => {showComments(instance, comment_id)});
}

function escapeHtml (unsafe) {
  return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function showComments(instance, comment_id) {
  const loadComment = document.getElementById("show_comments");
  const commentsList = document.getElementById("comments_list");

  loadComment.innerHTML = "Loading comments";
  fetch(instance + "/api/v1/statuses/" + comment_id + "/context")
    .then(function (response) {
      return response.json()
    })
    .then(function(data) {
      if (data.descendants && Array.isArray(data.descendants) && data.descendants.length > 0) {
        commentsList.innerHTML = ""
        data.descendants.forEach(function (reply) {
          reply.account.display_name = escapeHtml(reply.account.display_name)
          reply.account.emojis.forEach(emoji => {
            reply.account.display_name = reply.account.display_name.replace(":" + emoji.shortcode + ":",
                "<img src=\"" + escapeHtml(emoji.static_url) + "\" alt=\"Emoji " + emoji.shortcode + "\" height=\"20\" width=\"20\" />");
          });
          const commentContent = (reply.spoiler_text === "")
            ? reply.content
            : "<details><summary><span class=\"comment-cw\">&#x26A0; CW (click to open): " + escapeHtml(reply.spoiler_text) + "</span></summary>" + reply.content + "</details>"
          const commentClass = (reply.in_reply_to_id !== comment_id)
            ? "comment comment_reply"
            : "comment"
          const comment = ""
            + "<div id=\"comment-" + reply.id + "\" class=\"" + commentClass + "\">\n"
            + "  <div class=\"comment_author\">\n"
            + "    <a rel=\"external nofollow\" href=\"" + reply.uri + "\" rel=\"nofollow\">"
            + "      <img class=\"comment_avatar\" src=\"" + escapeHtml(reply.account.avatar_static) + "\" width=\"40px\" height=\"40px\" alt=\"Avatar of " + reply.account.display_name + "\">\n"
            + "      " + reply.created_at.substr(0, 10) + " : " + reply.account.display_name
            + "    </a>\n"
            + "  </div>\n"
            + "  <div class=\"comment_content\">" + commentContent + "</div>\n"
            + "  </div>\n"
            + "</div>\n";
          commentsList.appendChild(DOMPurify.sanitize(comment, { RETURN_DOM_FRAGMENT: true }));
        });
      } else {
        commentsList.innerHTML = "<p>No comments found</p>";
      }
    })
    .then(() => {loadComment.innerHTML = "Reload comments"});
}

