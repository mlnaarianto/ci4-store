<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h2>Chat User (Admin)</h2>

<div style="display:flex; gap:10px;">

    <!-- 🔹 LIST USER -->
    <div id="user-list" style="width:250px; border:1px solid #ccc; height:400px; overflow-y:auto;">
        <b>Daftar User</b>
    </div>

    <!-- 🔹 CHAT AREA -->
    <div style="flex:1;">
        <div id="chat-box" style="border:1px solid #ccc; padding:10px; height:350px; overflow-y:auto;">
            <i>Pilih user dulu...</i>
        </div>

        <br>

        <input type="text" id="message" placeholder="Ketik pesan...">
        <button onclick="sendMessage()">Kirim</button>
    </div>

</div>

<!-- 🔥 SESSION -->
<script>
const USER_ID = "<?= $user_id ?>";
const USERNAME = "<?= $username ?>";
const ROLE = "<?= $role ?>";
</script>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
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
  appId: "1:29761040885:web:d6e7108e40dba21db930bc"
};

// ================= INIT =================
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

// 🔥 STATE
let CURRENT_ROOM = null;
let unsubscribe = null;

// ================= REALTIME USER LIST =================
function loadUsers() {
  const userList = document.getElementById("user-list");

  const q = query(
    collection(db, "chats"),
    orderBy("updated_at", "desc")
  );

  onSnapshot(q, (snapshot) => {
    userList.innerHTML = "<b>Daftar User</b><hr>";

    snapshot.forEach(docSnap => {
      const data = docSnap.data();
      const roomId = docSnap.id;

      const div = document.createElement("div");
      div.style.padding = "8px";
      div.style.cursor = "pointer";
      div.style.borderBottom = "1px solid #eee";

      div.innerHTML = `
        <b>${data.name ?? 'User'}</b><br>
        <small>${data.last_message ?? ''}</small>
      `;

      div.onclick = () => loadChat(roomId);

      userList.appendChild(div);
    });
  });
}

// ================= LOAD CHAT =================
function loadChat(roomId) {
  CURRENT_ROOM = roomId;

  const chatBox = document.getElementById("chat-box");
  chatBox.innerHTML = "Loading...";

  // stop listener lama
  if (unsubscribe) unsubscribe();

  const q = query(
    collection(db, "chats", roomId, "messages"),
    orderBy("created_at")
  );

  unsubscribe = onSnapshot(q, (snapshot) => {
    chatBox.innerHTML = "";

    snapshot.forEach(docSnap => {
      const data = docSnap.data();

      const isMe = data.sender_role === "admin";

      const div = document.createElement("div");
      div.style.textAlign = isMe ? "right" : "left";
      div.style.marginBottom = "5px";

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

    chatBox.scrollTop = chatBox.scrollHeight;
  });
}

// ================= SEND MESSAGE =================
window.sendMessage = async function () {
  if (!CURRENT_ROOM) {
    alert("Pilih user dulu");
    return;
  }

  const input = document.getElementById("message");
  const text = input.value;

  if (!text) return;

  await addDoc(collection(db, "chats", CURRENT_ROOM, "messages"), {
    message: text,
    sender_id: USER_ID,
    sender_name: USERNAME,
    sender_role: ROLE,
    created_at: serverTimestamp()
  });

  // 🔥 update last message biar list realtime update
  await setDoc(doc(db, "chats", CURRENT_ROOM), {
    last_message: text,
    updated_at: serverTimestamp()
  }, { merge: true });

  input.value = "";
};

// ================= INIT =================
loadUsers();

</script>

<?= $this->endSection() ?>