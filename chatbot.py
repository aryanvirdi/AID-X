from fastapi import FastAPI
from pydantic import BaseModel
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # In production, specify allowed origins
    allow_methods=["*"],
    allow_headers=["*"],
)

class Message(BaseModel):
    message: str
    language: str  # 'en' or 'hi'

# English FAQ responses (keys lowercase)
faq_responses_en = {
    "what is aid x?": "AID-X is an AI-powered platform developed with the goal of efficiently coordinating aid requests and volunteer efforts during critical situations like natural disasters, pandemics, or any emergency that requires rapid humanitarian assistance. We provide a real-time map and chatbot interface to connect those who need help with those who can provide it.",
    "why was aid x made?": "AID-X was created to address the delay and inefficiency in traditional aid coordination. During crises, timely assistance is crucial and many requests go unmet due to lack of information and slow response time. Our platform leverages AI and real-time data visualization to streamline this process and save lives.",
    "what are the goals of aid x?": "Our primary goals are to empower communities in distress by providing fast access to essential aid, create a centralized hub for aid requests and volunteer coordination, improve transparency in aid distribution, and build a scalable system that works in diverse emergency scenarios.",
    "what does aid x want to achieve?": "Beyond immediate relief, AID-X aims to establish a sustainable ecosystem of volunteers and aid providers that remains active throughout the year, ensuring preparedness for future crises and fostering community resilience.",
    "who can access aid x?": "AID-X is accessible to a wide range of users: individuals in need of aid, volunteers willing to help, NGOs, government agencies, and any community members seeking to contribute or request resources.",
    "how does aid x work?": "Users submit their aid requests or volunteer information through our user-friendly interface. Requests are pinned on a real-time interactive map for visibility. Our chatbot provides realtime assistance, answering user queries with AI-powered natural language understanding to guide users and optimize aid delivery.",
    "how to put requests on aid x?": "To submit an aid request, register or log in to our platform, fill in the details of your needs (such as food, medical supplies, shelter), provide your location, and submit. Your request will then appear on the live map, alerting nearby volunteers and aid agencies.",
    "how to login or sign up?": "You can create a new account by providing your basic information and contact details using the sign-up form. If you already have an account, log in using your registered email and password. This allows you to submit requests, offer help, and track your interactions.",
}

# Hindi FAQ responses
faq_responses_hi = {
    "एड-एक्स क्या है?": "[एड-एक्स एक एआई-संचालित सहायता समन्वय मंच है जो प्राकृतिक आपदाओं, महामारियों या किसी भी आपातकालीन स्थिति में सहायता मांग और स्वयंसेवक प्रयासों के कुशल समन्वय के लिए विकसित किया गया है। हमारा प्लेटफ़ॉर्म एक वास्तविक समय मानचित्र और चैटबॉट इंटरफ़ेस प्रदान करता है जो मदद करने वालों और मदद मांगने वालों को जोड़ता है।]",
    "एड-एक्स क्यों बनाया गया?": "[एड-एक्स पारंपरिक सहायता समन्वय में होने वाली देरी और अक्षमता को दूर करने के लिए बनाया गया था। आपदाओं के दौरान, समय पर सहायता महत्त्वपूर्ण होती है और बहुत से अनुरोध सूचनाओं की कमी और धीमी प्रतिक्रियाओं के कारण पूरी नहीं हो पाते। हमारा प्लेटफ़ॉर्म एआई और वास्तविक समय डेटा विज़ुअलाइज़ेशन का उपयोग करता है ताकि इस प्रक्रिया को सुव्यवस्थित किया जा सके।]",
    "एड-एक्स के लक्ष्य क्या हैं?": "[हमारे प्रमुख लक्ष्य हैं: आपदाग्रस्त समुदायों को आवश्यक सहायता की त्वरित पहुँच प्रदान करना, सहायता अनुरोधों और स्वयंसेवकों के समन्वय के लिए एक केंद्रीकृत हब बनाना, सहायता वितरण में पारदर्शिता बढ़ाना, और एक ऐसा स्केलेबल सिस्टम बनाना जो विभिन्न आपातकालीन परिस्थितियों में कार्य कर सके।]",
    "एड-एक्स क्या हासिल करना चाहता है?": "[तत्काल राहत के अलावा, एड-एक्स का उद्देश्य स्वयंसेवकों और सहायता प्रदाताओं का एक स्थायी पारिस्थितिकी तंत्र स्थापित करना है जो पूरे वर्ष सक्रिय रहे, ताकि भविष्य की आपदाओं के लिए तैयारी सुनिश्चित की जा सके और समुदाय की मजबूती बढ़ाई जा सके।]",
    "कौन एड-एक्स का उपयोग कर सकता है?": "[एड-एक्स का उपयोग विभिन्न उपयोगकर्ता कर सकते हैं: सहायता की आवश्यकता वाले व्यक्ति, मदद करने के इच्छुक स्वयंसेवी, गैर सरकारी संगठन, सरकारी एजेंसियाँ, और कोई भी समुदाय सदस्य जो संसाधन प्रदान करना या मांगना चाहता है।]",
    "एड-एक्स कैसे काम करता है?": "[उपयोगकर्ता हमारे सहज इंटरफ़ेस के माध्यम से सहायता अनुरोध या स्वयंसेवक जानकारी सबमिट करते हैं। अनुरोध वास्तविक समय में इंटरैक्टिव मानचित्र पर दिखाए जाते हैं। हमारा चैटबॉट एआई-संचालित प्राकृतिक भाषा समझ के साथ उपयोगकर्ताओं के प्रश्नों का उत्तर देता है और सहायता वितरण में मार्गदर्शन करता है।]",
    "एड-एक्स पर अनुरोध कैसे भेजें?": "[सहायता अनुरोध सबमिट करने के लिए, हमारी साइट पर पंजीकरण या लॉगिन करें, अपनी जरूरतें (जैसे भोजन, चिकित्सा आपूर्ति, आश्रय) और स्थान दर्ज करें, और सबमिट करें। आपका अनुरोध लाइव मानचित्र पर दिखाई देगा जो आसपास के स्वयंसेवकों और एजेंसियों को सूचित करेगा।]",
    "लॉगिन या साइन अप कैसे करें?": "[नया खाता बनाने के लिए साइन-अप फॉर्म में अपनी बुनियादी जानकारी और संपर्क विवरण भरें। यदि आपका खाता पहले से है, तो अपने पंजीकृत ईमेल और पासवर्ड से लॉगिन करें। इससे आप अनुरोध सबमिट कर सकते हैं, मदद कर सकते हैं, और अपनी गतिविधियों को ट्रैक कर सकते हैं।]",
}

faq_responses = {
    "en": faq_responses_en,
    "hi": faq_responses_hi,
}

@app.post("/chat")
async def chat(message: Message):
    lang = message.language.lower()
    user_message = message.message.strip().lower()  # Lowercase for case-insensitivity

    responses = faq_responses.get(lang, faq_responses_en)
    answer = responses.get(user_message)
    if not answer:
        answer = "[कृपया पूर्वनिर्धारित सवाल चुनें या अपना प्रश्न टाइप करें।]" if lang == "hi" else "Please choose one of the preset questions or type your query."

    return {"reply": answer}
