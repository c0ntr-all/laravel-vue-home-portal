import { ref, onMounted } from 'vue';

export default function useWebSocket() {
  const socket = ref(null);

  const connect = () => {
    socket.value = new WebSocket('ws://home-portal.test:6001/app/any_app_key');

    socket.value.onopen = function(event) {
      console.log('WebSocket opened:', event);
    };

    socket.value.onmessage = function(event) {
      console.log('Message received from server:', event.data);
      // Обработайте полученное сообщение здесь
    };

    socket.value.onerror = function(error) {
      console.error('WebSocket error:', error);
    };

    socket.value.onclose = function(event) {
      console.log('WebSocket closed:', event);
    };
  };

  const disconnect = () => {
    if (socket.value && socket.value.readyState === WebSocket.OPEN) {
      socket.value.close();
    }
  };

  return { socket, connect, disconnect };
}
