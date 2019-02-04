var logEmail, logPwd, logBtn, logHide;
var signName, signSurName, signEmail, signNick, signPwd, signBtn, signHide;
$(document).ready(function () {
  logEmail = $("#logEmail");
  logHide = $("#logPwd");
  logBtn = $("#logSubmit");
  logPwd = logHide.prev();
  signName = $("#signName");
  signSurName = $("#signSur");
  signNick = $("#signNick");
  signEmail = $("#signEmail");
  signHide = $("#signPwd");
  signBtn = $("#signSubmit");
  signPwd = signHide.prev();
  logEmail.on({
    blur: function () {
      checkCredential($(this), true);
    },
    keyup: function () {
      checkCredential($(this), true);
    }
  });
  logPwd.on({
    blur: function () {
      checkText($(this));
    },
    keyup: function () {
      checkText($(this));
    }
  });
  signName.on({
    blur: function () {
      checkTextBack($(this));
    },
    keyup: function () {
      checkTextBack($(this));
    }
  });
  signSurName.on({
    blur: function () {
      checkTextBack($(this));
    },
    keyup: function () {
      checkTextBack($(this));
    }
  });
  signNick.on({
    blur: function () {
      checkCredential($(this), false);
    },
    keyup: function () {
      checkCredential($(this), false);
    }
  });
  signEmail.on({
    blur: function () {
      checkText($(this));
    },
    keyup: function () {
      checkText($(this));
    }
  });
  signPwd.on({
    blur: function () {
      checkText($(this));
    },
    keyup: function () {
      checkText($(this));
    }
  });
  logBtn.click(function () {
    var val = logPwd.val();
    var sha = SHA1(val);
    logHide.val(sha);
  });
  signBtn.click(function () {
    var val = signPwd.val();
    var sha = SHA1(val);
    signHide.val(sha);
  });
});
/**
 *
 */
function checkText($this) {
  var prev = $this.parent().prev();
  prev.find(".tag").remove();
  if ($this.val() == "") {
    var error = ' <span id="tag" class="tag is-danger">Need '+prev.text()+'</span>';
    prev.append(error);
  }
  activeButtonLogin();
  activeButtonSignin();
}
/**
 *
 */
function checkTextBack($this) {
  var par = $this.parent().parent().parent().parent();
  var prev = par.prev();
  prev.find(".tag").remove();
  if (signName.val() == "") {
    prev.append(' <span id="tag" class="tag is-danger">Need Name</span>');
  }
  if (signSurName.val() == "") {
    prev.append(' <span id="tag" class="tag is-danger">Need Surname</span>');
  }
  activeButtonLogin();
  activeButtonSignin();
}
/**
 *
 */
function checkCredential($this, login) {
  if ($this.val() != "") {
    $.post("log/check", { "nickname" : $this.val() }, function (result) {
        var prev = $this.parent().prev();
        prev.find(".tag").remove();
        if (result == 1) {
          if (login) {
            var match = ' <span id="tag" class="tag is-success">Correct account</span>';
            $this.addClass("confirm");
          } else {
            var match = ' <span id="tag" class="tag is-danger">This nickname is already used</span>';
            $this.removeClass("confirm");
          }
          prev.append(match);
        } else {
          if (login) {
            var mismatch = ' <span id="tag" class="tag is-warning">Sorry, this account doesn\'t exist...</span>';
            $this.removeClass("confirm");
          } else {
            var mismatch = ' <span id="tag" class="tag is-success">This nickname is not used</span>';
            $this.addClass("confirm");
          }
          prev.append(mismatch);
        }
        activeButtonLogin();
        activeButtonSignin();
    });
  } else {
    checkText($this);
  }
}
/**
 *
 */
function activeButtonLogin() {
  if (logEmail.val() != "" && logPwd.val() != "" && logEmail.hasClass("confirm")) {
    logBtn.removeClass("is-disabled");
  } else if (!logBtn.hasClass("is-disabled")) {
    logBtn.addClass("is-disabled");
  }
}
function activeButtonSignin() {
  if (signEmail.val() != "" && signPwd.val() != "" && signName.val() != "" &&
      signSurName.val() != "" && signNick.val() != "" && signNick.hasClass("confirm")) {
    signBtn.removeClass("is-disabled");
  } else if (!signBtn.hasClass("is-disabled")) {
    signBtn.addClass("is-disabled");
  }
}
