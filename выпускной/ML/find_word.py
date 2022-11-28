import pickle 
import matplotlib.pyplot as plt
import numpy as np
import sys

try:
    arg = sys.argv[1]
except:
    print("error")
    quit()

try:
    with open("TengriNews.pkl", "rb") as pkl_handle:
        my_dict = pickle.load(pkl_handle)
        my_dict = dict(reversed(my_dict.items()))
    message = arg.lower()
    fin_dict = dict()
    for word in [message]:
        new_dict = dict()
        for el in my_dict:
            if my_dict[el]["news_content"] and word in my_dict[el]["news_content"].lower():
                if my_dict[el]["news_publication_time"][5:7] in new_dict.keys():
                    new_dict[my_dict[el]["news_publication_time"][5:7]] += 1
                else:
                    new_dict[my_dict[el]["news_publication_time"][5:7]] = 1
        fin_dict[word] = new_dict

    X = np.array(list(fin_dict[message].keys()))
    lst = []
    for el in fin_dict.keys():
        lst.append((np.array(list(fin_dict[el].values())),el))
    for el in lst:
        plt.plot(X, el[0], label=el[1])
    
    plt.xlabel("месяц")
    plt.ylabel("Кол-во упоминаний")
    plt.title("Упоминания '" + message + "' в тексте новостей TengriNews в 2021 г.")
    
    plt.savefig("test.png", format="png")
    print("done")
except:
    print("error")