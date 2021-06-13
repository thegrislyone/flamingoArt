import { mount } from '@vue/test-utils'
import MessageInput from '../../components/Chat/MessageInput.vue'

describe('MessageInput.vue', () => {

  test('MessageInput is a vue instance', () => {
    
    const wrapper = mount(MessageInput)

    expect(wrapper.vm).toBeTruthy()

  })

  test('MessageInput is works right', async () => {
    
    const wrapper = mount(MessageInput)
    const input = wrapper.find('input')

    input.element.value = 'some value'

    await input.trigger('input')

    expect(wrapper.vm.message).toBe('some value')

  })

  test('Called send message when enter pressed', () => {

    const wrapper = mount(MessageInput)
    const input = wrapper.find('input')

    const messageSend = jest.spyOn(wrapper.vm, 'messageSend');

    input.trigger('keydown.enter')

    expect(messageSend).toHaveBeenCalled()

  })

});
