//----------------------------------------------------------------------------
// Like
//----------------------------------------------------------------------------
$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

/**
 * Toggle like for a given type & id
 */
function toggleLike(type, id) {
    if (AUTH_USER) {
        if (!LIKE_URL) {
            return;
        }

        $.post(LIKE_URL, { id, type }).done(function (res) {
            let isAdding = res.status === "added";
            updateLikeIconState(type, id, isAdding);
            if (isAdding) showHeartAnimation();
        });
    } else {
        const likes = JSON.parse(localStorage.getItem("likes") || "{}");
        likes[type] = likes[type] || [];
        let index = likes[type].indexOf(id);
        let isAdding = index === -1;

        if (isAdding) {
            likes[type].push(id);
            showHeartAnimation();
        } else {
            likes[type].splice(index, 1);
        }

        localStorage.setItem("likes", JSON.stringify(likes));
        updateLikeIconState(type, id, isAdding);
    }
}

/**
 * Update the heart icon for an item
 */
function updateLikeIconState(type, id, isLiked) {
    $(`.item-card[data-id="${id}"][data-type="${type}"] .like-btn i`)
        .toggleClass("fas", isLiked) // filled
        .toggleClass("far", !isLiked); // outline
}

function showHeartAnimation(themeColor = "#4E2D45") {
    const heart = document.createElement("div");
    // heart.textContent = "❤️";
    heart.innerHTML = `<svg viewBox="0 0 24 24" width="80" height="80" fill="${themeColor}">
        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42
                4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81
                14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4
                6.86-8.55 11.54L12 21.35z"/>
        </svg>`;
    heart.style.position = "fixed";
    heart.style.zIndex = "9999";
    heart.style.top = "50%";
    heart.style.left = "50%";
    heart.style.transform = "translate(-50%, -50%) scale(0)";
    heart.style.fontSize = "80px";
    heart.style.color = themeColor;
    heart.style.opacity = "0";
    heart.style.pointerEvents = "none";
    heart.style.transition =
        "transform 0.5s ease, opacity 0.5s ease, top 0.5s ease";

    document.body.appendChild(heart);

    // Force browser to register the initial state
    heart.getBoundingClientRect();

    // Animate in
    heart.style.transform = "translate(-50%, -60%) scale(1.3)";
    heart.style.opacity = "1";

    // Animate out after 0.5s
    setTimeout(() => {
        heart.style.transform = "translate(-50%, -80%) scale(0)";
        heart.style.opacity = "0";
    }, 500);

    // Remove from DOM after animation
    setTimeout(() => heart.remove(), 1000);
}
//----------------------------------------------------------------------------

//----------------------------------------------------------------------------
// Bookmark
//----------------------------------------------------------------------------
const TYPE_MAP = {
    quran: "App\\Models\\QuranVerse",
    hadith: "App\\Models\\HadithVerse",
    topic: "App\\Models\\Topic",
};

function types(type) {
    return TYPE_MAP[type] || null;
}

// ======== Helpers ======== //
async function existInCollection(collectionId) {
    const clt = CollectionList.find((c) => c.id === collectionId);

    return (
        clt?.items?.some(
            (item) =>
                item.bookmarkable_type == types(BookmarkType) &&
                item.bookmarkable_id == BookmarkItem
        ) || false
    );
}

function removeFromCollection(collectionId, type, itemId) {
    CollectionList = CollectionList.map((clt) => {
        if (clt.id === collectionId) {
            return {
                ...clt,
                items: clt.items.filter(
                    (item) =>
                        !(
                            item.bookmarkable_type == types(type) &&
                            item.bookmarkable_id == itemId
                        )
                ),
            };
        }
        return clt;
    });
}

// ======== Main ======== //
$(document).on("click", ".bookmark-btn", function () {
    toastr.clear();

    if (!AUTH_USER) {
        toastr.error("Please login to save bookmarks");
        return;
    }

    BookmarkItem = $(this).data("id");
    BookmarkType = $(this).data("type");

    renderCollectionList();
    $("#collectionModal").modal("show");
});

async function renderCollectionList() {
    const $list = $("#collectionList").empty();
    CollectionList =
        CollectionList.length > 0
            ? CollectionList
            : await $.get(COLLECTION_FETCH_URL);

    if (CollectionList.length === 0) {
        $list.html(`<p class="text-center text-muted">No collections yet.</p>`);
        return;
    }

    for (const clt of CollectionList) {
        const exists = await existInCollection(clt.id);
        const tick = exists
            ? `<i class="fas fa-check text-success"></i>`
            : `<i class="fa-solid fa-xmark text-danger"></i>`;

        $list.append(`
            <li class="list-group-item collection-item d-flex justify-content-between align-items-center" style="cursor:pointer;"
                data-name="${clt.name}" data-id="${clt.id}" data-type="${BookmarkType}">
                <span class="text-capitalize">${clt.name}</span> <span class="tick">${tick}</span>
            </li>
        `);
    }
}

$(document).on("click", ".collection-item", function () {
    toastr.clear();

    CollectionName = $(this).data("name");
    CollectionId = $(this).data("id");

    saveToCollection();
});

// Create new collection
$("#createCollectionBtn").on("click", function () {
    toastr.clear();
    const newName = $.trim($("#newCollectionName").val()).toLowerCase();
    if (!newName) {
        toastr.error("Please enter a collection name");
        return;
    }

    saveToCollection(true);
    $("#newCollectionName").val("");
});

async function saveToCollection(createCollection = false) {
    toastr.clear();
    let name = "";
    let isAdding = false;

    if (createCollection) {
        name = $("#newCollectionName").val();

        await $.post(COLLECTION_URL, {
            BookmarkItem,
            BookmarkType,
            name,
        }).done(async function (res) {
            const collection = res.collection;
            if (res.newCollection) {
                CollectionList.push(collection);
            }

            isAdding = res.status === "added";
            await renderCollectionList(BookmarkType, BookmarkItem);
            updateCollectionItemState(BookmarkType, collection.id, isAdding);
            updateBookmarkIconState(BookmarkType, BookmarkItem, isAdding);
        });
    } else {
        name = CollectionName;
        await $.post(BOOKMARK_URL, {
            BookmarkItem,
            BookmarkType,
            CollectionId,
        }).done(function (res) {
            isAdding = res.status === "added";

            if (isAdding) {
                CollectionList = CollectionList.map((clt) => {
                    if (clt.id === CollectionId) {
                        return {
                            ...clt,
                            items: [...clt.items, res.collectionItem],
                        };
                    }
                    return clt;
                });
            } else {
                removeFromCollection(CollectionId, BookmarkType, BookmarkItem);
            }

            updateCollectionItemState(BookmarkType, CollectionId, isAdding);
            updateBookmarkIconState(BookmarkType, BookmarkItem, res.found);
        });
    }

    if (isAdding) toastr.success(`Saved to ${name}`);
}

function updateBookmarkIconState(type, id, bookmarked) {
    $(`.item-card[data-id="${id}"][data-type="${type}"] .bookmark-btn i`)
        .toggleClass("fas", bookmarked) // filled
        .toggleClass("far", !bookmarked); // outline
}

function updateCollectionItemState(type, id, isTicked) {
    const tick = isTicked
        ? `<i class="fas fa-check text-success"></i>`
        : `<i class="fa-solid fa-xmark text-danger"></i>`;

    $(`.collection-item[data-id="${id}"][data-type="${type}"] .tick`).html(
        tick
    );
}
//----------------------------------------------------------------------------

//----------------------------------------------------------------
// Play Ayah
//----------------------------------------------------------------
let currentAudio = null;
let currentBtn = null;
let currentCard = null;

async function playAudio() {
    const $btn = $(this);
    const surah = $btn.data("surah");
    const ayah = $btn.data("ayah");
    const $ayahCard = $btn.closest(".ayah-card");
    const $icon = $btn.find("i");

    // Toggle pause/resume if same button clicked
    if (currentAudio && currentBtn && currentBtn.is($btn)) {
        if (currentAudio.paused) {
            currentAudio.play();
            $icon.removeClass("far fa-play-circle").addClass("fas fa-pause");
            $ayahCard.addClass("playing");
        } else {
            currentAudio.pause();
            $icon.removeClass("fas fa-pause").addClass("far fa-play-circle");
            $ayahCard.removeClass("playing");
        }
        return;
    }

    // Stop previous audio if different button clicked
    if (currentAudio) {
        currentAudio.pause();
        currentBtn
            .find("i")
            .removeClass("fas fa-pause")
            .addClass("far fa-play-circle");
        currentCard.removeClass("playing");
        currentAudio = null;
        currentBtn = null;
        currentCard = null;
    }

    try {
        const apiUrl = `https://api.alquran.cloud/v1/ayah/${surah}:${ayah}/ar.alafasy`;
        const response = await fetch(apiUrl);
        const json = await response.json();

        if (json?.data?.audio) {
            const audioUrl = json.data.audio;
            currentAudio = new Audio(audioUrl);
            currentBtn = $btn;
            currentCard = $ayahCard;

            currentAudio.play();
            $icon.removeClass("far fa-play-circle").addClass("fas fa-pause");
            $ayahCard.addClass("playing");

            currentAudio.onended = function () {
                $icon
                    .removeClass("fas fa-pause")
                    .addClass("far fa-play-circle");
                $ayahCard.removeClass("playing");
                currentAudio = null;
                currentBtn = null;
                currentCard = null;
            };
        } else {
            toastr.error("Audio not found for this ayah.");
        }
    } catch (err) {
        console.error(err);
        toastr.error("Error fetching audio.");
    }
}
//----------------------------------------------------------------
