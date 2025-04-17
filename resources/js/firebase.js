// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import axios from "axios";

// Ambil firebase config dari Laravel (dinamis dari Blade / backend)
const firebaseConfig = window.firebaseConfig ?? {};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Listener pesan masuk
onMessage(messaging, async (payload) => {
  console.log("Message received. ", payload);

  const payloadData = {
    title: payload.notification ? payload.notification.title : "No Title",
    message: payload.notification ? payload.notification.body : "No Body",
    data: payload.data ? payload.data : {},
  };

  try {
    const response = await axios.post("/api/push-notification", payloadData);

    console.log("Data sent successfully:", response.data);

    if (window.Livewire) {
      Livewire.dispatch("push-notification");
    }
  } catch (error) {
    console.error("Error sending data:", error);
  }
});

// Export dan expose ke window
window.messaging = messaging;
window.getToken = getToken;
export default app;
