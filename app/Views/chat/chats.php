<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h2>Halaman Chat</h2>

<div id="chat-box" style="border:1px solid #ccc; padding:10px; width:300px; height:300px; overflow-y:auto;">
</div>

<br>

<input type="text" id="message" placeholder="Ketik pesan...">
<button onclick="sendMessage()">Kirim</button>

<!-- 🔥 SESSION USER -->
<script>
  const USER_ID = "<?= $user_id ?>";
  const USERNAME = "<?= $username ?>";
  const ROLE = "<?= $role ?>";

  // ✅ ROOM DINAMIS (INI YANG PENTING)
  const ROOM_ID = "user_" + USER_ID;
</script>

<script type="module">
  // ================= IMPORT FIREBASE =================
  import {
    initializeApp
  } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
  import {
    getFirestore,
    collection,
    addDoc,
    query,
    orderBy,
    onSnapshot,
    serverTimestamp,
    doc,
    setDoc
  } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

  // ================= CONFIG =================
  const firebaseConfig = {
    apiKey: "AIzaSyDB8tw1NjyI7IjGYD2mfAStz2HZMwqVCjI",
    authDomain: "ci4-chat.firebaseapp.com",
    projectId: "ci4-chat",
    storageBucket: "ci4-chat.firebasestorage.app",
    messagingSenderId: "29761040885",
    appId: "1:29761040885:web:d6e7108e40dba21db930bc",
    measurementId: "G-V95WYYWTKY"
  };

  // ================= INIT =================
  const app = initializeApp(firebaseConfig);
  const db = getFirestore(app);

  // ================= INIT ROOM (AUTO CREATE) =================
  await setDoc(doc(db, "chats", ROOM_ID), {
    name: USERNAME,
    user_id: USER_ID,
    updated_at: serverTimestamp()
  }, {
    merge: true
  });

  // ================= REALTIME LISTENER =================
  const q = query(
    collection(db, "chats", ROOM_ID, "messages"),
    orderBy("created_at")
  );

  onSnapshot(q, (snapshot) => {
    const chatBox = document.getElementById("chat-box");
    chatBox.innerHTML = "";

    snapshot.forEach((docSnap) => {
      const data = docSnap.data();

      const isMe = data.sender_id == USER_ID;

      const div = document.createElement("div");
      div.style.marginBottom = "5px";
      div.style.textAlign = isMe ? "right" : "left";

      div.innerHTML = `
      <div style="
        display:inline-block;
        background:${isMe ? '#DCF8C6' : '#eee'};
        padding:8px;
        border-radius:10px;
        max-width:70%;
      ">
        <small>${data.sender_name}</small><br>
        ${data.message}
      </div>
    `;

      chatBox.appendChild(div);
    });

    chatBox.scrollTo({
      top: chatBox.scrollHeight,
      behavior: "smooth"
    });
  });

  // ================= SEND MESSAGE =================
  window.sendMessage = async function() {
    const input = document.getElementById("message");
    const text = input.value;

    if (!text) {
      alert("Pesan tidak boleh kosong");
      return;
    }

    await addDoc(collection(db, "chats", ROOM_ID, "messages"), {
      message: text,
      sender_id: USER_ID,
      sender_name: USERNAME,
      sender_role: ROLE,
      created_at: serverTimestamp()
    });

    // 🔥 update last message (buat admin list)
    await setDoc(doc(db, "chats", ROOM_ID), {
      last_message: text,
      updated_at: serverTimestamp()
    }, {
      merge: true
    });

    input.value = "";
  };

  // ================= ENTER TO SEND =================
  document.getElementById("message").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
      sendMessage();
    }
  });
</script>

<?= $this->endSection() ?>